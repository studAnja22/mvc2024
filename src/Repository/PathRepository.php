<?php

namespace App\Repository;

use App\Entity\Path;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Path>
 */
class PathRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Path::class);
    }
    /**
     * Find the path with the from_room id.
     *
     * @param string $from_room the rooms id.
     * @return Path[] Returns array with Path object.
     */
    public function findPathsFromRoom(string $from_room): ?array
    {
        /** @var Path[] $result */
        $result = $this->createQueryBuilder('b')
            ->andWhere('b.from_room = :from_room')
            ->setParameter('from_room', $from_room)
            ->getQuery()
            ->getResult()
        ;
        return $result;
    }
}
