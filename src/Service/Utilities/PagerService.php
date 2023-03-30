<?php

declare(strict_types=1);

namespace App\Service\Utilities;

use App\Exception\OutOfIndexPagerException;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RequestStack;

class PagerService
{
    public function __construct(
        private int $rowsPerPage,
        private RequestStack $requestStack,
    ){}

    /**
     * Creates and configures a pager from a query builder
     *
     * @param \Doctrine\ORM\QueryBuilder $builder
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function createAndConfigurePager(QueryBuilder $builder): Pagerfanta
    {
        $pagerfanta = new Pagerfanta(new QueryAdapter($builder));
        $pagerfanta->setMaxPerPage($this->rowsPerPage);
        $request = $this->requestStack->getCurrentRequest();
        $pageNumber = $request->query->getInt('page', 1);
        if ($pageNumber > $pagerfanta->getNbPages()) {
            throw new OutOfIndexPagerException('Page number is out of range');
        }
        $pagerfanta->setCurrentPage($pageNumber);
        return $pagerfanta;
    }
}