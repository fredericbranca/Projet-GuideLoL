<?php

namespace App\Repository;

use App\Entity\Evaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evaluation>
 *
 * @method Evaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evaluation[]    findAll()
 * @method Evaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evaluation::class);
    }

    /**
     * @return Notation Float - Retourne la moyenne du guide
     */
    public function getMoyenneGuides(): array
    {
        $query = $this->createQueryBuilder('e')
            ->select('g.id', 'ROUND(AVG(e.notation),1) AS moyenne')
            ->join('App\Entity\Guide', 'g')
            ->where('g.id = e.guide')
            ->andwhere('e.notation IS NOT NULL')
            ->groupBy('e.guide')
            ->getQuery();

        $moyennes = $query->getResult();

        $moyennesGuides = [];
    
        foreach ($moyennes as $moyenne) {
            $id = $moyenne['id'];
            $moyennesGuides[$id] = $moyenne;
        }

        return $moyennesGuides;
    }

    //    public function findOneBySomeField($value): ?Evaluation
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
