<?php

namespace App\Repository;

use App\Entity\Guide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Guide>
 *
 * @method Guide|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guide|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guide[]    findAll()
 * @method Guide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guide::class);
    }

    // Récupère la liste des guides par date (modifié ou création) + gère les filtres champion et voie (role)
    public function findByDateWithFilters($filters)
    {
        $qb = $this->createQueryBuilder('g');

        // Conditions basées sur les filtres
        if (isset($filters['champion']) && $filters['champion']) {
            $qb->andWhere('g.champion = :champion')
                ->setParameter('champion', $filters['champion']);
        }

        if (isset($filters['role']) && $filters['role']) {
            $qb->andWhere('g.voie = :voie')
                ->setParameter('voie', $filters['role']);
        }

        // Trie par date de modification si elle existe sinon par date de création
        $qb->orderBy('CASE WHEN g.modified_at IS NOT NULL THEN g.modified_at ELSE g.created_at END', 'DESC');

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Guide[] Returns an array of Guide objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Guide
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
