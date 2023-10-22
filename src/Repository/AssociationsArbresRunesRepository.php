<?php

namespace App\Repository;

use App\Entity\AssociationsArbresRunes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssociationsArbresRunes>
 *
 * @method AssociationsArbresRunes|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssociationsArbresRunes|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssociationsArbresRunes[]    findAll()
 * @method AssociationsArbresRunes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationsArbresRunesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssociationsArbresRunes::class);
    }

//    /**
//     * @return AssociationsArbresRunes[] Returns an array of AssociationsArbresRunes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AssociationsArbresRunes
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
