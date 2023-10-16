<?php

namespace App\Repository;

use App\Entity\DataStatistiqueBonus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataStatistiqueBonus>
 *
 * @method DataStatistiqueBonus|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataStatistiqueBonus|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataStatistiqueBonus[]    findAll()
 * @method DataStatistiqueBonus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataStatistiqueBonusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataStatistiqueBonus::class);
    }

//    /**
//     * @return DataStatistiqueBonus[] Returns an array of DataStatistiqueBonus objects
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

//    public function findOneBySomeField($value): ?DataStatistiqueBonus
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
