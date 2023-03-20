<?php

declare(strict_types=1);

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

}
