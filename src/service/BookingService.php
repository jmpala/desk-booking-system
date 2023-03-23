<?php

declare(strict_types=1);


namespace App\service;

use App\dto\BookableInformationDTO;
use App\dto\SeatmapStatusDTO;
use App\Entity\Bookings;
use App\Exception\BookingOverlapException;
use App\Exception\NotAuthorizedException;
use App\Repository\BookableRepository;
use App\Repository\BookingsRepository;
use App\Repository\UnavailableDatesRepository;
use App\Repository\UserRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\SecurityBundle\Security;

class BookingService
{
    public function __construct(
        private Security $security,
        private BookableRepository $bookableRepository,
        private BookingsRepository $bookingRepository,
        private UnavailableDatesRepository $unavailableDatesRepository,
        private UserRepository $userRepository,
        private PagerService $pagerService
    )
    {
    }

    /**
     * Returns the status of all seats for a given date
     *
     * @param \DateTime $date
     * @return SeatmapStatusDTO
     * @throws \Exception
     */
    public function retrieveSeatStatusByDate(\DateTime $date): SeatmapStatusDTO
    {
        /** @var Array<\App\Entity\Bookable> $bookables */
        $bookables = $this->bookableRepository->getAllBookableAndRelatedCategories();
        if (empty($bookables)) {
            throw new \Exception('No bookables where found, please contact the administrator');
        }

        /** @var Array<\App\Entity\Bookings> $bookings */
        $bookings = $this->bookingRepository->getBookingsWithBookable($date);

        $unavailableDates = $this->unavailableDatesRepository->getUnavailableDatesByDate($date);

        $seatmapStatusDTO = new SeatmapStatusDTO($date);

        // We add all the bookables to the DTO
        foreach ($bookables as $bookable) {
            $bookableInformationDTO = new BookableInformationDTO();
            $bookableInformationDTO->populateWithBookableEntity($bookable);
            $seatmapStatusDTO->addBookable($bookableInformationDTO);
        }

        $user = $this->security->getUser();
        $userUdentifier = '';
        if ($user) {
            $userUdentifier = $user->getUserIdentifier();
        }

        // We set the state of bookables that are booked
        foreach ($bookings as $booking) {
            foreach ($seatmapStatusDTO->getBookables() as $bookableInformationDTO) {
                if ($bookableInformationDTO->getBookableId() === $booking->getBookable()->getId()) {
                    if ($booking->getUser()->getUserIdentifier() === $userUdentifier) {
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
     * Creates a new bookings for the given bookable and date-range
     *
     * To prevent errors when booking the same bookable with similar dates at
     * the same time, @function isBookableAlreadyBookedByDateRange is called.
     * In case duplicates are found, @throws \App\Exception\BookingOverlapException
     *
     * @param int       $bookableId
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return \App\Entity\Bookings
     * @throws \Exception
     */
    public function createNewBooking(
        int $bookableId,
        \DateTime $fromDate,
        \DateTime $toDate
    ): Bookings {

        if ($this->isBookableAlreadyBookedByDateRange($bookableId, $fromDate, $toDate)) {
            throw new BookingOverlapException('The bookable is already booked for the given date range');
        }

        $bookable = $this->bookableRepository->find($bookableId);
        $user = $this->security->getUser();

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
     * Creates a new bookings for the given bookable, date-range and userid
     *
     * To prevent errors when booking the same bookable with similar dates at
     * the same time, @function isBookableAlreadyBookedByDateRange is called.
     * In case duplicates are found, @throws \App\Exception\BookingOverlapException
     *
     * @param int       $bookableId
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @param int       $userId
     *
     * @return \App\Entity\Bookings
     * @throws \Exception
     */
    public function createNewBookingByUserId(
        int $bookableId,
        \DateTime $fromDate,
        \DateTime $toDate,
        int $userId
    ): Bookings
    {
        if ($this->isBookableAlreadyBookedByDateRange($bookableId, $fromDate, $toDate)) {
            throw new BookingOverlapException('The bookable is already booked for the given date range');
        }

        $bookable = $this->bookableRepository->find($bookableId);
        $user = $this->userRepository->find($userId);

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
     * @param int    $pageNumber
     * @param string $columnName
     * @param string $oder
     * @param string $pastBookings
     * @param int    $userId
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function getAllBookingsPagedByUserID(
        int $pageNumber,
        string $columnName,
        string $oder,
        string $pastBookings,
        int $userId
    ): Pagerfanta {
        return  $this->pagerService->createAndConfigurePager(
            $this->bookingRepository->getAllBookingsByUserIDOrderedByColumnQueryBuilder($columnName, $oder, $pastBookings, $userId),
            $pageNumber
        );
    }

    /**t
     * Deletes the given booking
     *
     * @param int $bookingId
     *
     * @return void
     * @throws \App\Exception\NotAuthorizedException
     */
    public function deleteBooking(int $bookingId): void
    {
        $todayDate = new \DateTime((new \DateTime())->format('Y-m-d'));
        $booking = $this->bookingRepository->find($bookingId);

        if (!$booking) {
            throw new \RuntimeException('Booking not found');
        }

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

}