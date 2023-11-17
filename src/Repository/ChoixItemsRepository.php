<?php

namespace App\Repository;

use App\Entity\ChoixItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChoixItems>
 *
 * @method ChoixItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChoixItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChoixItems[]    findAll()
 * @method ChoixItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoixItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChoixItems::class);
    }

//    /**
//     * @return ChoixItems[] Returns an array of ChoixItems objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ChoixItems
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
