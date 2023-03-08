<?php

declare(strict_types=1);

namespace App\service;

use App\Entity\Bookable;
use App\Repository\BookableRepository;

class BookableService
{
    public function __construct(
        private BookableRepository $bookableRepository,
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
        /** @var array<\App\Entity\Bookable> $res */
        $res = $this->bookableRepository->checkAvailabilityByDate($id, $from, $to);

        $status = [
            'isAvailable' => empty($res)
        ];

        if (!$status['isAvailable']) {
            foreach ($res as $bookable) {
                $status['bookings'] = $this->extractBookings($bookable);
                $status['unavailableDates'] = $this->extractUnavailableDates($bookable);
            }
        }

        return $status;
    }

    /**
     * Extracts the bookings from the bookables or empty array when none found
     *
     * @param \App\Entity\Bookable $bookables
     *
     * @return array<\App\Entity\Bookings> | []
     */
    private function extractBookings(Bookable $bookables): array
    {
        $bookings = [];
        foreach ($bookables->getBookings() as $booking) {
            $bookings[] = [
                'id' => $booking->getId(),
                'from' => $booking->getStartDate()->format('Y-m-d'),
                'to' => $booking->getEndDate()->format('Y-m-d'),
                'bookableCode' => $booking->getBookable()->getCode(),
            ];
        }
        return $bookings;
    }

    /**
     * Extracts the unavailable dates from the bookables or empty array when none found
     *
     * @param \App\Entity\Bookable $bookables
     *
     * @return array<\App\Entity\UnavailableDates> | []
     */
    public function extractUnavailableDates(Bookable $bookables): array
    {
        $unavailableDates = [];
        foreach ($bookables->getUnavailableDates() as $unavailableDate) {
            $unavailableDates[] = [
                'id' => $unavailableDate->getId(),
                'from' => $unavailableDate->getStartDate()->format('Y-m-d'),
                'to' => $unavailableDate->getEndDate()->format('Y-m-d'),
                'notes' => $unavailableDate->getNotes(),
                'bookableCode' => $unavailableDate->getBookable()->getCode(),
            ];
        }
        return $unavailableDates;
    }
}