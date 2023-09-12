<?php

namespace App\Repository;

use App\Entity\DataSortInvocateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataSortInvocateur>
 *
 * @method DataSortInvocateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataSortInvocateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataSortInvocateur[]    findAll()
 * @method DataSortInvocateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataSortInvocateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataSortInvocateur::class);
    }

//    /**
//     * @return DataSortInvocateur[] Returns an array of DataSortInvocateur objects
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

//    public function findOneBySomeField($value): ?DataSortInvocateur
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
