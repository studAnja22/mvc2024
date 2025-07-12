<?php

namespace App\Repository;

use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Library>
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }
    /**
     * Find the book with the exact isbn number or returns null if unable to find it.
     *
     * @param string $isbn The books ISBN.
     * @return Library|null Returns one Library object or null.
     */
    public function findOneBySomeField(string $isbn): ?Library
    {
        /** @var Library|null $result */
        $result = $this->createQueryBuilder('b')
            ->andWhere('b.isbn = :isbn')
            ->setParameter('isbn', $isbn)
            ->getQuery()
            ->getOneOrNullResult()
        ;
        return $result;
    }
    /**
     * Find all library books having a value above the specified one.
     *
     * @param int $value The minimum value to filter library books
     * @return Library[] Returns an array of Library objects
     */
    public function findByMinimumValue(int $value): array
    {
        /** @var Library[] $result */
        $result = $this->createQueryBuilder('p')
            ->andWhere('p.isbn >= :isbn')
            ->setParameter('isbn', $value)
            ->orderBy('p.isbn', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        return $result;
    }

    /**
     * Find all products having a value above the specified one with SQL.
     *
     *@param int $value The minimal value to filter products
     * @return array<array<string, mixed>> Returns an array of arrays (i.e. a raw data set)
     */
    public function findByMinimumValue2(int $value): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM product AS p
            WHERE p.value >= :value
            ORDER BY p.value ASC
        ';

        $resultSet = $conn->executeQuery($sql, ['value' => $value]);

        return $resultSet->fetchAllAssociative();
    }
    //    /**
    //     * @return Library[] Returns an array of Library objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }


}
