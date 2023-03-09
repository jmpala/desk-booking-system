<?php

declare(strict_types=1);


namespace App\service;

use App\dto\BookableInformationDTO;
use App\dto\SeatmapStatusDTO;
use App\Entity\Bookings;
use App\Repository\BookableRepository;
use App\Repository\BookingsRepository;
use App\Repository\UnavailableDatesRepository;
use Symfony\Bundle\SecurityBundle\Security;

class BookingService
{
    public function __construct(
        private Security $security,
        private BookableRepository $bookableRepository,
        private BookingsRepository $bookingRepository,
        private UnavailableDatesRepository $unavailableDatesRepository
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
}