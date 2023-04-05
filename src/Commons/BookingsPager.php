<?php

declare(strict_types=1);

namespace App\Commons;

class BookingsPager extends \Pagerfanta\Pagerfanta
{
    private bool $hasOngoingBookings = false;
    private bool $hasPastBookings = false;

    public function __construct($adapter, $maxPerPage = 10, $currentPage = 1)
    {
        parent::__construct($adapter);
        $this->setMaxPerPage($maxPerPage);
        $this->setCurrentPage($currentPage);
    }


}