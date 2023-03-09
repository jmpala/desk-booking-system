<?php

namespace App\Repository;

use App\Entity\UnavailableDates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnavailableDates::class);
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
     * @param \DateTime $date
     *
     * @return array
     */
    public function getUnavailableDatesByDate(\DateTime $date): array
    {
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
}
