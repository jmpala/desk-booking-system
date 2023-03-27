<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UnavailableDatesRepository;
use Pagerfanta\Pagerfanta;

class AdminService
{
    public function __construct(
        private UnavailableDatesRepository $unavailableDatesRepository,
        private PagerService $pagerService
    )
    {}

    /**
     * Returns a pager that handles all the @UnavailableDates
     *
     * @param int    $pageNum
     * @param string $columnName
     * @param string $oder
     * @param string $pastUnavailableDates
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function getAllUnavailableDatesPaged(int $pageNum, string $columnName, string $oder, string $pastUnavailableDates): Pagerfanta
    {
        return $this->pagerService->createAndConfigurePager(
            $this->unavailableDatesRepository->getAllUnavailableDatesWithOrderedColumn($columnName, $oder, $pastUnavailableDates),
            $pageNum
        );
    }
}