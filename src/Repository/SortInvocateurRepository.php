<?php

namespace App\Repository;

use App\Entity\SortInvocateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SortInvocateur>
 *
 * @method SortInvocateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method SortInvocateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method SortInvocateur[]    findAll()
 * @method SortInvocateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortInvocateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SortInvocateur::class);
    }

//    /**
//     * @return SortInvocateur[] Returns an array of SortInvocateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SortInvocateur
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
