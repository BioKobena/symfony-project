<?php

namespace App\Repository;

use App\Entity\Developer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Developer>
 */
class DeveloperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Developer::class);
    }


    public function findByCriteria(array $criteria): array
{
    $qb = $this->createQueryBuilder('d');

    if (!empty($criteria['salary'])) {
        $qb->andWhere('d.salaireMin >= :salary')
           ->setParameter('salary', $criteria['salary']);
    }

    if (!empty($criteria['location'])) {
        $qb->andWhere('d.localisation LIKE :location')
           ->setParameter('location', '%' . $criteria['location'] . '%');
    }

    if (!empty($criteria['experience'])) {
        $qb->andWhere('d.experience >= :experience')
           ->setParameter('experience', $criteria['experience']);
    }

    return $qb->getQuery()->getResult();
}

    //    /**
    //     * @return Developer[] Returns an array of Developer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Developer
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
