<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bookings;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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
    private Request $request;

    public function __construct(
        private ManagerRegistry $registry,
        private RequestStack $requestStack,
    )
    {
        parent::__construct($registry, Bookings::class);
        $this->request = $this->requestStack->getCurrentRequest();
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
     * @return array
     * @throws \Exception
     */
    public function getBookingsWithBookableByDate(\DateTime $date): array {
        return $this->createQueryBuilder('b')
            ->select('b', 'bk')
            ->leftJoin('b.bookable', 'bk')
            ->where('DATE(:date) BETWEEN DATE(b.start_date)  AND DATE(b.end_date)')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns all the bookings for a given bookable id and date range
     *
     * @param int       $id
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return array
     */
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
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * Generates and returns a QueryBuilder for all bookings by user id,
     * ordered by column
     *
     * It can also filter past bookings
     *
     * @param string $columnName
     * @param string $oder
     * @param string $pastBookings
     * @param int    $userId
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllBookingsByUserIDOrderedByColumnQueryBuilder(
        string $columnName,
        string $oder,
        string $pastBookings,
        int $userId
    ): QueryBuilder {
        $selectedColumn = match ($columnName) {
            'resource' => 'bk.code',
            'confirmation' => 'b.confirmation',
            'startdate' => 'b.start_date',
            'enddate' => 'b.end_date',
            default => 'b.start_date',
        };

        $selectedOrder = match ($oder) {
            'asc' => 'ASC',
            'desc' => 'DESC',
            default => 'ASC',
        };

        $selectedPast = match ($pastBookings) {
            'true' => '',
            'false' => ' AND b.end_date >= CURRENT_DATE()',
            default => ' AND b.end_date >= CURRENT_DATE()',
        };

        return $this->createQueryBuilder('b')
            ->select('b', 'bk')
            ->leftJoin('b.bookable', 'bk')
            ->where('b.user = :id' . $selectedPast)
            ->orderBy($selectedColumn, $selectedOrder)
            ->setParameter(
                'id',
                $userId);
    }

    /**
     * Returns the number of bookings for a given user
     *
     * @param int $userID
     *
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllBookingsByUserID(int $userID): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b)')
            ->where('b.user = :id')
            ->setParameter('id', $userID)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Returns the number of ongoing bookings for a given user
     *
     * @param int $userID
     *
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllNonPastBookingsByUserID(int $userID): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b)')
            ->where('b.user = :id')
            ->andWhere('DATE(b.end_date) >= DATE(CURRENT_DATE())')
            ->setParameter('id', $userID)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
