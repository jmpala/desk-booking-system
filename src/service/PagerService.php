<?php

declare(strict_types=1);

namespace App\service;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class PagerService
{
    public function __construct(
        private int $rowsPerPage
    ){}

    /**
     * Creates and configures a pager from a query builder
     *
     * @param \Doctrine\ORM\QueryBuilder $builder
     * @param int                        $pageNumber
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function createAndConfigurePager(QueryBuilder $builder, int $pageNumber): Pagerfanta
    {
        $pagerfanta = new Pagerfanta(new QueryAdapter($builder));
        $pagerfanta->setMaxPerPage($this->rowsPerPage);
        $pageNumber  = min($pageNumber, $pagerfanta->getNbPages());
        $pagerfanta->setCurrentPage($pageNumber);
        return $pagerfanta;
    }
}