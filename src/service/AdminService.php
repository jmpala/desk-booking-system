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

    public function getAllUnavailableDates(string $columnName, string $oder, string $pastUnavailableDates): Pagerfanta
    {
        return $this->unavailableDatesRepository->getAllUnavailableDatesWithOrderedColumn($columnName, $oder, $pastUnavailableDates);
    }
}