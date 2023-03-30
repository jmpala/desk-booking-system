<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UnavailableDatesRepository;
use App\Service\Utilities\PagerService;
use Pagerfanta\Pagerfanta;

class AdminService
{
    public function __construct(
        private PagerService $pagerService,
        private UnavailableDatesRepository $unavailableDatesRepository
    )
    {}

    /**
     * Returns a pager that handles all the @UnavailableDates
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function getAllUnavailableDatesPaged(): Pagerfanta
    {
        return $this->pagerService->createAndConfigurePager(
            $this->unavailableDatesRepository->getAllUnavailableDatesOrderedColumn()
        );
    }
}