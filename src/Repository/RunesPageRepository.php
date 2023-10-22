<?php

namespace App\Repository;

use App\Entity\RunesPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RunesPage>
 *
 * @method RunesPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method RunesPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method RunesPage[]    findAll()
 * @method RunesPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RunesPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RunesPage::class);
    }

//    /**
//     * @return RunesPage[] Returns an array of RunesPage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RunesPage
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
