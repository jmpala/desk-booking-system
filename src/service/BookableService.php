<?php

declare(strict_types=1);

namespace App\service;

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
}