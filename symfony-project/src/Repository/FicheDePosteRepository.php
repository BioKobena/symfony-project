<?php

namespace App\Repository;

use App\Entity\FicheDePoste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FicheDePoste>
 */
class FicheDePosteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheDePoste::class);
    }

    public function searchFiches(array $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('f');

        if (!empty($criteria['salairePropose'])) {
            $queryBuilder->andWhere('f.salairePropose >= :salairePropose')
                ->setParameter('salairePropose', $criteria['salairePropose']);
        }

        if (!empty($criteria['localisation'])) {
            $queryBuilder->andWhere('f.localisation LIKE :localisation')
                ->setParameter('localisation', '%' . $criteria['localisation'] . '%');
        }

        if (!empty($criteria['niveauExperience'])) {
            $queryBuilder->andWhere('f.niveauExperience <= :niveauExperience')
                ->setParameter('niveauExperience', $criteria['niveauExperience']);
        }

        // if (!empty($criteria['technologies'])) {
        //     $queryBuilder->andWhere('f.technologies LIKE :technologies')
        //         ->setParameter('technologies', '%' . implode(',', $criteria['technologies']) . '%');
        // }

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return FicheDePoste[] Returns an array of FicheDePoste objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FicheDePoste
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
