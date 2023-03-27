<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Bookable;
use App\Entity\UnavailableDates;
use App\Repository\UnavailableDatesRepository;

class UnavailableDatesService
{
    public function __construct(
        private UnavailableDatesRepository $unavailableDatesRepository,
    )
    {}

    /**
     * Simple wrapper to find an Unavailable Dates entity by id
     *
     * @param int $id
     *
     * @return \App\Entity\UnavailableDates|null
     */
    public function findById(int $id): ?UnavailableDates
    {
        return $this->unavailableDatesRepository->find($id);
    }

    /**
     * Returns the matching Unavailable Dates for the given date range, with the
     * posivility to igrnore a specific Unavailable Dates entity by id, used for
     * editing
     *
     * @param \App\Entity\Bookable              $bookable
     * @param \DateTime                         $from
     * @param \DateTime                         $to
     * @param \App\Entity\UnavailableDates|null $ignore
     *
     * @return \App\Entity\UnavailableDates[]
     */
    public function checkAvailabilityByDate(Bookable $bookable, \DateTime $from, \DateTime $to, ?UnavailableDates $ignore = null): array
    {
        $data = [
            'isAvailable' => true,
            'unavailableDates' => [],
        ];

        /* @var Array<UnavailableDates> $unavailableDates */
        $unavailableDates = $this->unavailableDatesRepository->getUnavailableDatesByDateRange($bookable, $from, $to);
        if ($ignore) {
            $unavailableDates = array_filter($unavailableDates, static fn ($ud) => $ud->getId() !== $ignore->getId());
        }

        if ($unavailableDates) {
            $data['isAvailable'] = false;
            $data['unavailableDates'] = $this->extractUnavailableDates($unavailableDates);
        }

        return $data;
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
}