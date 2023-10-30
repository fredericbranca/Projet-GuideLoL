<?php

namespace App\Repository;

use App\Entity\EnsembleItemsGroups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EnsembleItemsGroups>
 *
 * @method EnsembleItemsGroups|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnsembleItemsGroups|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnsembleItemsGroups[]    findAll()
 * @method EnsembleItemsGroups[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnsembleItemsGroupsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnsembleItemsGroups::class);
    }

//    /**
//     * @return EnsembleItemsGroups[] Returns an array of EnsembleItemsGroups objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EnsembleItemsGroups
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
