<?php

declare(strict_types=1);

namespace App\service;

use App\Repository\UnavailableDatesRepository;
use Pagerfanta\Pagerfanta;

class AdminService
{
    public function __construct(
        private UnavailableDatesRepository $unavailableDatesRepository,
    )
    {}

    /**
     * Gets unavailable dates and setup the pager, with the given page number, column name, order and past unavailable dates
     * (if selected by the user)
     *
     * @param int    $pageNum
     * @param string $columnName
     * @param string $oder
     * @param string $pastUnavailableDates
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function getAllUnavailableDates(int $pageNum, string $columnName, string $oder, string $pastUnavailableDates): Pagerfanta
    {
        $pagerFanta = $this->unavailableDatesRepository->getAllUnavailableDatesWithOrderedColumn($columnName, $oder, $pastUnavailableDates);
        $pagerFanta->setMaxPerPage(10);

        $pageNum  = ($pageNum > $pagerFanta->getNbPages()) ? min($pageNum, $pagerFanta->getNbPages()) : $pageNum;
        $pagerFanta->setCurrentPage($pageNum);

        return $pagerFanta;
    }
}