<?php

namespace App\Repository;

use App\Entity\Bookings;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @extends ServiceEntityRepository<Bookings>
 *
 * @method Bookings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookings[]    findAll()
 * @method Bookings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookings::class);
    }

    public function save(Bookings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Bookings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Retrieves all bookings that overlap the given date
     *
     * @param \DateTime $date
     *
     * @return array
     */
    public function getBookingsWithBookable(DateTime $date): array {
        return $this->createQueryBuilder('b')
            ->select('b', 'bk')
            ->leftJoin('b.bookable', 'bk')
            ->where('DATE(:date) BETWEEN DATE(b.start_date)  AND DATE(b.end_date)')
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getResult();
    }

    public function getAllBookingsByBookableIdAndDateRange(
        int $id,
        DateTime $from,
        DateTime $to
    ): array {
        return $this->createQueryBuilder('b')
            ->select('b', 'bk')
            ->leftJoin('b.bookable', 'bk')
            ->where('bk.id = :id AND
            DATE(:from) <= DATE(b.end_date) AND DATE(:to) >= DATE(b.start_date)')
            ->setParameter('from', $from->format('Y-m-d'))
            ->setParameter('to', $to->format('Y-m-d'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    /**
     *
     *
     * @param int $getId
     * @return Pagerfanta
     */
    public function getAllBookingsByID(int $getId): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->select('b', 'bk')
            ->leftJoin('b.bookable', 'bk')
            ->where('b.user = :id')
            ->setParameter('id', $getId)
            ->getQuery();

        $pagerFanta = new Pagerfanta(new QueryAdapter($queryBuilder));

        return $pagerFanta;
    }

}
