<?php

namespace App\Repository;

use App\Entity\OrdreItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrdreItems>
 *
 * @method OrdreItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdreItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdreItems[]    findAll()
 * @method OrdreItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdreItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdreItems::class);
    }

//    /**
//     * @return OrdreItems[] Returns an array of OrdreItems objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OrdreItems
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
