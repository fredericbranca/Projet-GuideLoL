<?php

namespace App\Repository;

use App\Entity\CompetencesGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompetencesGroup>
 *
 * @method CompetencesGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetencesGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetencesGroup[]    findAll()
 * @method CompetencesGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetencesGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetencesGroup::class);
    }

//    /**
//     * @return CompetencesGroup[] Returns an array of CompetencesGroup objects
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

//    public function findOneBySomeField($value): ?CompetencesGroup
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
