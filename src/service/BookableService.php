<?php

declare(strict_types=1);

namespace App\service;

use App\Repository\BookableRepository;
use App\Repository\BookingsRepository;
use App\Repository\UnavailableDatesRepository;

class BookableService
{
    public function __construct(
        private BookableRepository $bookableRepository,
        private BookingsRepository $bookingsRepository,
        private UnavailableDatesRepository $unavailableDatesRepository,
    )
    {
    }

    /**
     * Returns all the bookables with their related categories
     *
     * @return array
     */
    public function getAllBookableAndRelatedCategories(): array
    {
        return $this->bookableRepository->getAllBookableAndRelatedCategories();
    }

    /**
     * Checks if the given bookable is available for the given dates. The returnung array will contain booking and
     * unavailable dates data when the bookable is not available, otherwise this data will be empty. For convienience
     * the isAvailable key will be set to true or false, depending the case.
     *
     * @param int       $id
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return bool[]
     */
    public function checkAvailabilityByDate(int $id, \DateTime $from, \DateTime $to): array
    {
        /** @var array<\App\Entity\Bookings> $bookings */
        $bookings = $this->bookingsRepository->getAllBookingsByBookableIdAndDateRange($id, $from, $to);

        /** @var array<\App\Entity\UnavailableDates> $unavailable_dates */
        $unavailable_dates = $this->unavailableDatesRepository->getAllUnavailableDatesByBookableIdAndDateRange($id, $from, $to);

        $status = [
            'isAvailable' => empty($bookings) && empty($unavailable_dates)
        ];

        if (!$status['isAvailable']) {
            $status['bookings'] = $this->extractBookings($bookings);
            $status['unavailableDates'] = $this->extractUnavailableDates($unavailable_dates);
        }

        return $status;
    }

    /**
     * Maps all the bookings to a front-end format or empty array when none found
     *
     * @param array<\App\Entity\Bookings> $bookings
     *
     * @return array<\App\Entity\Bookings> | []
     */
    private function extractBookings(array $bookings): array
    {
        $mappedBookings = [];
        foreach ($bookings as $booking) {
            $mappedBookings[] = [
                'id' => $booking->getId(),
                'from' => $booking->getStartDate()->format('Y-m-d'),
                'to' => $booking->getEndDate()->format('Y-m-d'),
                'bookableCode' => $booking->getBookable()->getCode(),
            ];
        }
        return $mappedBookings;
    }

    /**
     * Maps all the unavailable dates to a front-end format or empty array when none found
     *
     * @param array<\App\Entity\UnavailableDates> $unavailableDates
     *
     * @return array<\App\Entity\UnavailableDates> | []
     */
    public function extractUnavailableDates(array $unavailableDates): array
    {
        $mappedUnavailables = [];
        foreach ($unavailableDates as $unavailableDate) {
            $mappedUnavailables[] = [
                'id' => $unavailableDate->getId(),
                'from' => $unavailableDate->getStartDate()->format('Y-m-d'),
                'to' => $unavailableDate->getEndDate()->format('Y-m-d'),
                'notes' => $unavailableDate->getNotes(),
                'bookableCode' => $unavailableDate->getBookable()->getCode(),
            ];
        }
        return $mappedUnavailables;
    }
}