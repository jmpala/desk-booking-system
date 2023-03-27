<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Bookable;
use App\Entity\Bookings;
use App\Entity\UnavailableDates;
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
     * Just another normal find by id
     *
     * @param int $id
     *
     * @return \App\Entity\Bookable|null
     */
    public function findById(int $id): ?Bookable
    {
        return $this->bookableRepository->find($id);
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
     * The $ignore parameter is used to ignore a booking when checking availability. This is used when updating a booking
     *
     * @param int       $id
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return bool[]
     */
    public function checkAvailabilityByDate(int $id, \DateTime $from, \DateTime $to, ?Bookings $ignore = null): array
    {
        /** @var array<\App\Entity\Bookings> $bookings */
        $bookings = $this->bookingsRepository->getAllBookingsByBookableIdAndDateRange($id, $from, $to);

        if ($ignore) {
            $bookings = array_filter($bookings, static fn (Bookings $booking) => $booking->getId() !== $ignore->getId());
        }

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
    private function extractUnavailableDates(array $unavailableDates): array
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

    /**
     * Creates new Unavailable Dates
     *
     * @param int                        $bookableId
     * @param float|bool|int|string|null $fromDate
     * @param float|bool|int|string|null $toDate
     * @param float|bool|int|string|null $notes
     *
     * @return void
     */
    public function createUnavailableDates(
        int $bookableId,
        string $fromDate,
        string $toDate,
        string $notes
    ): UnavailableDates {
        $bookable = $this->bookableRepository->find($bookableId);
        $unavailableDates = new UnavailableDates();
        $unavailableDates->setBookable($bookable);
        $unavailableDates->setStartDate(new \DateTime($fromDate));
        $unavailableDates->setEndDate(new \DateTime($toDate));
        $unavailableDates->setNotes($notes);

        $this->unavailableDatesRepository->save($unavailableDates, true);

        return $unavailableDates;
    }

    /**
     *
     *
     * @param int $unavailableDateId
     *
     * @return void
     */
    public function deleteUnavailableDate(int $unavailableDateId): void
    {
        try {
            $unavailableDate = $this->unavailableDatesRepository->find($unavailableDateId);
            $this->unavailableDatesRepository->remove($unavailableDate, true);
        } catch (\Exception $e) {
            throw new \Exception('Unable to delete unavailable date id: ' . $unavailableDateId);
        }
    }

    public function editUnavailableDates(
        int $unavailableDateId,
        \DateTime $fromDate,
        \DateTime $toDate,
        string $notes
    ): UnavailableDates {
        $unavailableDate = $this->unavailableDatesRepository->find($unavailableDateId);

        $unavailableDate->setStartDate($fromDate);
        $unavailableDate->setEndDate($toDate);
        $unavailableDate->setNotes($notes);

        $this->unavailableDatesRepository->save($unavailableDate, true);

        return $unavailableDate;
    }
}