<?php

namespace App\Repository;

use App\Entity\DataItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataItem>
 *
 * @method DataItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataItem[]    findAll()
 * @method DataItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataItem::class);
    }

//    /**
//     * @return DataItem[] Returns an array of DataItem objects
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

//    public function findOneBySomeField($value): ?DataItem
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
