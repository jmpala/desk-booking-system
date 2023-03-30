<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bookable;
use App\Entity\UnavailableDates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @extends ServiceEntityRepository<UnavailableDates>
 *
 * @method UnavailableDates|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnavailableDates|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnavailableDates[]    findAll()
 * @method UnavailableDates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnavailableDatesRepository extends ServiceEntityRepository
{
    private Request $request;
    
    public function __construct(
        private ManagerRegistry $registry,
        private RequestStack $requestStack,
    )
    {
        parent::__construct($registry, UnavailableDates::class);
        $this->request = $this->requestStack->getCurrentRequest();
    }

    public function save(UnavailableDates $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UnavailableDates $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Retrives all unavailale date that overlap the given date
     *
     * @return array
     */
    public function getUnavailableDatesByDate(): array
    {
        $date = new \DateTime($this->request->get('date'));
        return  $this->createQueryBuilder('u')
            ->select('u', 'bk')
            ->leftJoin('u.bookable', 'bk')
            ->where('DATE(:date) BETWEEN DATE(u.start_date) AND DATE(u.end_date)')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    public function getAllUnavailableDatesByBookableIdAndDateRange(
        int $id,
        \DateTime $from,
        \DateTime $to
    ): array {
        return $this->createQueryBuilder('u')
            ->select('u', 'bk')
            ->leftJoin('u.bookable', 'bk')
            ->where('bk.id = :id AND
            DATE(:from) <= DATE(u.end_date) AND DATE(:to) >= DATE(u.start_date)')
            ->setParameter('id', $id)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return all the unavailable dates with the selected column and order
     *
     * We can set the pastUnavailableDates to 'true' to get also the past unavailable dates
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllUnavailableDatesOrderedColumn(): QueryBuilder
    {
        $selectedColumn = match (
            $this->request->query->getAlpha('col', 'bookable')
        ) {
            'bookable' => 'bk.code',
            'startdate' => 'ud.start_date',
            'enddate' => 'ud.end_date',
            default => 'ud.start_date',
        };

        $selectedOrder = match (
            $this->request->query->getAlpha('ord', 'asc')
        ) {
            'asc' => 'ASC',
            'desc' => 'DESC',
            default => 'ASC',
        };

        $selectedPast =
            $this->request->query->getBoolean('past', false)
                ? ''
                : 'AND ud.end_date >= CURRENT_DATE()'
        ;

        return $this->createQueryBuilder('ud')
            ->select('ud', 'bk')
            ->leftJoin('ud.bookable', 'bk')
            ->where('1 = 1' . $selectedPast)
            ->orderBy($selectedColumn, $selectedOrder);
    }

    /**
     * Return all the unavailable dates that overlap the given date range
     *
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return array
     */
    public function getUnavailableDatesByDateRange(
        Bookable $bookable,
        \DateTime $from,
        \DateTime $to
    ): array {
        return $this->createQueryBuilder('ud')
            ->select('ud', 'bk')
            ->leftJoin('ud.bookable', 'bk')
            ->where('DATE(:from) <= DATE(ud.end_date) AND DATE(:to) >= DATE(ud.start_date)')
            ->andWhere(':id = bk.id')
            ->setParameter('id', $bookable->getId())
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }
}
