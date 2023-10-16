<?php

namespace App\Repository;

use App\Entity\DataRune;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataRune>
 *
 * @method DataRune|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataRune|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataRune[]    findAll()
 * @method DataRune[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataRuneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataRune::class);
    }

//    /**
//     * @return DataRune[] Returns an array of DataRune objects
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

//    public function findOneBySomeField($value): ?DataRune
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
