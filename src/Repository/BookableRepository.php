<?php

namespace App\Repository;

use App\Entity\Bookable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bookable>
 *
 * @method Bookable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookable[]    findAll()
 * @method Bookable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookable::class);
    }

    public function save(Bookable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Bookable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllBookableAndRelatedCategories(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'c')
            ->leftJoin('b.category', 'c')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Bookable[] Returns an array of Bookable objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Bookable
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function checkAvailabilityByDate(int $id, \DateTime $from, \DateTime $to): array
    {
        return $this->createQueryBuilder('bk')
            ->select('bk', 'c', 'ud', 'b')
            ->leftJoin('bk.category', 'c')
            ->leftJoin('bk.unavailableDates', 'ud')
            ->leftJoin('bk.bookings', 'b')
            ->where('bk.id = :id AND (
                                                DATE(:from) BETWEEN DATE(ud.start_date) AND DATE(ud.end_date)
                                                OR DATE(:to) BETWEEN DATE(ud.start_date) AND DATE(ud.end_date)
                                                OR DATE(:from) BETWEEN DATE(b.start_date) AND DATE(b.end_date)
                                                OR DATE(:to) BETWEEN DATE(b.start_date) AND DATE(b.end_date))')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
