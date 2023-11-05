<?php

namespace App\Repository;

use App\Entity\DataCompetence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataCompetence>
 *
 * @method DataCompetence|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataCompetence|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataCompetence[]    findAll()
 * @method DataCompetence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCompetenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataCompetence::class);
    }

//    /**
//     * @return DataCompetence[] Returns an array of DataCompetence objects
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

//    public function findOneBySomeField($value): ?DataCompetence
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
