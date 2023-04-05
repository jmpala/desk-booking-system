<?php

declare(strict_types=1);


namespace App\Service;

use App\Commons\RequestParameters;
use App\dto\BookableInformationDTO;
use App\dto\SeatmapStatusDTO;
use App\Entity\Bookings;
use App\Exception\BookingOverlapException;
use App\Exception\NotAuthorizedException;
use App\Repository\BookableRepository;
use App\Repository\BookingsRepository;
use App\Repository\UnavailableDatesRepository;
use App\Repository\UserRepository;
use App\Service\Utilities\PagerService;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class BookingService
{
    public const HAS_BOOKINGS = 'has_bookings_';
    public const HAS_ONGOING_BOOKINGS = 'has_ongoing_bookings_';
    public const HAS_ONLY_PAST_BOOKINGS = 'has_only_past_bookings_';
    private Request $request;

    public function __construct(
        private Security $security,
        private BookableRepository $bookableRepository,
        private BookingsRepository $bookingRepository,
        private UnavailableDatesRepository $unavailableDatesRepository,
        private UserRepository $userRepository,
        private RequestStack $requestStack,
        private PagerService $pagerService,
    )
    {
        $this->request = $this->requestStack->getCurrentRequest();
    }

    /**
     * Returns the status of all seats for a given date
     *
     * @return SeatmapStatusDTO
     * @throws \Exception
     */
    public function retrieveSeatStatusByDate(): SeatmapStatusDTO
    {
        $date = new \DateTime($this->request->get('date'));

        /** @var Array<\App\Entity\Bookable> $bookables */
        $bookables = $this->bookableRepository->getAllBookableAndRelatedCategories();
        if (empty($bookables)) {
            throw new \Exception('No bookables where found, please contact the administrator');
        }

        /** @var Array<\App\Entity\Bookings> $bookings */
        $bookings = $this->bookingRepository->getBookingsWithBookableByDate($date);

        /** @var Array<\App\Entity\UnavailableDates> $unavailableDates */
        $unavailableDates = $this->unavailableDatesRepository->getUnavailableDatesByDate($date);

        $seatmapStatusDTO = new SeatmapStatusDTO(
            date: $date,
        );

        // We add all the bookables to the DTO
        foreach ($bookables as $bookable) {
            $bookableInformationDTO = new BookableInformationDTO($bookable);
            $seatmapStatusDTO->addBookable($bookableInformationDTO);
        }

        $user = $this->security->getUser();
        $userIdentifier = '';
        if ($user) {
            $userIdentifier = $user->getUserIdentifier();
        }

        // We set the state of bookables that are booked
        foreach ($bookings as $booking) {
             foreach ($seatmapStatusDTO->getBookables() as $bookableInformationDTO) {
                if ($bookableInformationDTO->getBookableId() === $booking->getBookable()->getId()) {
                    if ($booking->getUser()->getUserIdentifier() === $userIdentifier) {
                        $bookableInformationDTO->setIsBookedByLoggedUser(true);
                    }
                    $bookableInformationDTO->populateWithBookingEntity($booking);
                }
            }
        }

        // We set the state of bookables that are unavailable
        foreach ($unavailableDates as $unavailableDate) {
            foreach ($seatmapStatusDTO->getBookables() as $bookableInformationDTO) {
                if ($bookableInformationDTO->getBookableId() === $unavailableDate->getBookable()->getId()) {
                    $bookableInformationDTO->populateWithUnavailableDatesEntity($unavailableDate);
                }
            }
        }

        // We set bookables that are unavailable
        return $seatmapStatusDTO;
    }

    /**
     * Creates a new bookings for the given as request parameters
     * bookable and date-range
     *
     * To prevent errors when booking the same bookable with similar dates at
     * the same time, @function isBookableAlreadyBookedByDateRange is called.
     * In case duplicates are found,
     *
     * @return \App\Entity\Bookings
     * @throws \App\Exception\BookingOverlapException
     */
    public function createNewBooking(
    ): Bookings {
        return $this->createNewBookingByUserId(
            $this->security->getUser()->getId()
        );
    }

    /**
     * Creates a new bookings for the given as request parameter
     * bookable and date-range and userid as parameter
     *
     * To prevent errors when booking the same bookable with similar dates at
     * the same time, @function isBookableAlreadyBookedByDateRange is called.
     * In case duplicates are found,
     *
     * @param int $userId
     *
     * @return \App\Entity\Bookings
     * @throws \App\Exception\BookingOverlapException
     */
    public function createNewBookingByUserId(
        int $userId
    ): Bookings
    {
        $bookableId = $this->request->request->getInt(RequestParameters::BOOKABLE_ID);
        $fromDate = new \DateTime($this->request->request->get(RequestParameters::FROM_DATE));
        $toDate = new \DateTime($this->request->request->get(RequestParameters::TO_DATE));

        if ($this->isBookableAlreadyBookedByDateRange($bookableId, $fromDate, $toDate)) {
            throw new BookingOverlapException('The bookable is already booked for the given date range');
        }

        $bookable = $this->bookableRepository->find($bookableId);

        $user = ($userId === $this->security->getUser()->getId())
            ? $this->security->getUser()
            : $this->userRepository->find($userId)
        ;

        $newBooking = new Bookings();
        $newBooking->setBookable($bookable);
        $newBooking->setStartDate($fromDate);
        $newBooking->setEndDate($toDate);
        $newBooking->setUser($user);
        $newBooking->setConfirmation(bin2hex(random_bytes(4)));

        $this->bookingRepository->save($newBooking, true);

        return $newBooking;
    }

    /**
     * Checks if the given bookable is already booked by the given date range, optionally excluding the given booking
     *
     * @param int                       $bookableId
     * @param \DateTime                 $from
     * @param \DateTime                 $to
     * @param \App\Entity\Bookings|null $ignore
     *
     * @return bool
     */
    public function isBookableAlreadyBookedByDateRange(int $bookableId, \DateTime $from, \DateTime $to, ?Bookings $ignore = null): bool
    {
        $bookings = $this->bookingRepository->getAllBookingsByBookableIdAndDateRange($bookableId, $from, $to);

        if ($ignore) {
            $bookings = array_filter($bookings, static fn (Bookings $booking) => $booking->getId() !== $ignore->getId());
        }

        return !empty($bookings);
    }

    /**
     * Returns all bookings for the given user
     *
     * @param int    $userId
     *
     * @return \Pagerfanta\Pagerfanta
     * @throws \App\Exception\OutOfIndexPagerException
     */
    public function getAllBookingsPagedByUserID(
        int $userId
    ): Pagerfanta {
        $this->updateSessionShowPastBookings($userId);
        return  $this->pagerService->createAndConfigurePager(
            $this->bookingRepository->getAllBookingsByUserIDOrderedByColumnQueryBuilder($userId)
        );
    }

    /**t
     * Deletes booking by a given id
     *
     * @param int $bookingId
     *
     * @return void
     * @throws \App\Exception\NotAuthorizedException
     */
    public function deleteBooking(int $bookingId): void
    {
        $booking = $this->bookingRepository->find($bookingId);
        if (!$booking) {
            throw new \RuntimeException('Booking not found');
        }

        $todayDate = new \DateTime();
        $todayDate->setTime(0, 0, 0);
        if ($booking->getEndDate() < $todayDate) {
            throw new NotAuthorizedException('You are not allowed to delete past bookings');
        }

        $this->bookingRepository->remove($booking, true);
    }

    /**
     * Get the number of bookings for the given user
     *
     * @param int $userID
     *
     * @return int
     */
    public function countAllBookingsByUserID(int $userID): int
    {
        return $this->bookingRepository->countAllBookingsByUserID($userID);
    }

    /**
     * Counts all the actual and future bookings for the given user
     *
     * @param int $userID
     *
     * @return int
     */
    public function countAllNonPastBookingsByUserID(int $userID): int
    {
        return $this->bookingRepository->countAllNonPastBookingsByUserID($userID);
    }

    /**
     * Finds a booking by its ID
     *
     * @param int $bookingId
     *
     * @return \App\Entity\Bookings|null
     */
    public function findById(int $bookingId): ?Bookings
    {
        return $this->bookingRepository->find($bookingId);
    }

    /**
     * Edit an existing booking
     *
     * @param int       $bookingId
     * @param int       $bookableId
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return \App\Entity\Bookings
     * @throws \App\Exception\BookingOverlapException
     */
    public function editBooking(
        int $bookingId,
        int $bookableId,
        \DateTime $fromDate,
        \DateTime $toDate
    ): Bookings {

        $booking = $this->bookingRepository->find($bookingId);
        $bookable = $this->bookableRepository->find($bookableId);

        if ($this->isBookableAlreadyBookedByDateRange($bookableId, $fromDate, $toDate, $booking)) {
            throw new BookingOverlapException('The bookable is already booked for the given date range');
        }

        $booking->setBookable($bookable);
        $booking->setStartDate($fromDate);
        $booking->setEndDate($toDate);

        $this->bookingRepository->save($booking, true);

        return $booking;
    }

    /**
     * Updates the session to keep track if the user has bookings or not, and
     * if the user has past bookings or not
     *
     * @param int $userId
     *
     * @return void
     */
    public function updateSessionShowPastBookings(int $userId): void
    {
        $hasBookings = $this->countAllBookingsByUserID($userId) >= 1;
        $hasOngoingBookings = $this->countAllNonPastBookingsByUserID($userId) >= 1;

        $this->requestStack->getSession()->set(
            $this::HAS_BOOKINGS . $userId,
            $hasBookings
        );

        $this->requestStack->getSession()->set(
            $this::HAS_ONGOING_BOOKINGS . $userId,
            $hasOngoingBookings
        );

        $this->requestStack->getSession()->set(
            $this::HAS_ONLY_PAST_BOOKINGS . $userId,
            $hasBookings && !$hasOngoingBookings
        );
    }
}