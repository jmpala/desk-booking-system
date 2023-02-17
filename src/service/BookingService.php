<?php

declare(strict_types=1);


namespace App\service;

use App\dto\BookableInformationDTO;
use App\dto\SeatmapStatusDTO;
use App\Repository\BookableRepository;
use App\Repository\BookingsRepository;
use App\Repository\UnavailableDatesRepository;

class BookingService
{
    public function __construct(
        private BookableRepository $bookableRepository,
        private BookingsRepository $bookingRepository,
        private UnavailableDatesRepository $unavailableDatesRepository,
    )
    {
    }

    /**
     * Returns the status of all seats for a given date
     *
     * @param \DateTime $date
     * @return array
     * @throws \Exception
     */
    public function retrieveSeatsStatusPerDate(\DateTime $date): SeatmapStatusDTO
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

        // We set the state of bookables that are booked
        foreach ($bookings as $booking) {
            foreach ($seatmapStatusDTO->getBookables() as $bookableInformationDTO) {
                if ($bookableInformationDTO->getBookableId() === $booking->getBookable()->getId()) {
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

}