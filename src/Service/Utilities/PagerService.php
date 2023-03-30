<?php

declare(strict_types=1);

namespace App\Service\Utilities;

use App\Exception\OutOfIndexPagerException;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PagerService
{
    private Request $request;

    public function __construct(
        private int $rowsPerPage,
        private RequestStack $requestStack,
    )
    {
        $this->request = $this->requestStack->getCurrentRequest();
    }

    /**
     * Creates and configures a pager from a query builder. It uses the page
     * query parameter to set up the page number
     *
     * - page= # or default 1
     * Ex: http://localhost:8000/bookings?page=2
     *
     * In case the @OutOfIndexPagerException is thrown, the @ExceptionSubscriber
     * catches it and handles the error
     *
     * @param \Doctrine\ORM\QueryBuilder $builder
     *
     * @return \Pagerfanta\Pagerfanta
     * @throws \App\Exception\OutOfIndexPagerException
     */
    public function createAndConfigurePager(QueryBuilder $builder): Pagerfanta
    {
        $pagerfanta = new Pagerfanta(new QueryAdapter($builder));
        $pagerfanta->setMaxPerPage($this->rowsPerPage);
        $pageNumber = $this->request->query->getInt('page', 1);
        if ($pageNumber > $pagerfanta->getNbPages()) {
            throw new OutOfIndexPagerException('Page number is out of range');
        }
        $pagerfanta->setCurrentPage($pageNumber);
        return $pagerfanta;
    }
}