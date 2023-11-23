<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    // Méthode pour trouver le plus grand nombre utilisé dans les pseudos
    public function getIncrementedLastNumberUsed(): int
    {
        $qb = $this->createQueryBuilder('u') // création d'un constructeur de requêtes pour la table user
            ->select('u.pseudo') // spécifie la colonne à récupérer dans le requête
            ->where('u.pseudo LIKE :prefix') // Définit les conditions de filtrage des données
            ->setParameter('prefix', 'user%') 
            ->orderBy('u.pseudo', 'DESC') // Détermine le tri des résultats (plus grand au plus petit)
            ->setMaxResults(1); // Limite le nombre de résultat retourné par la requête à 1

        $lastPseudo = $qb->getQuery()->getSingleScalarResult(); // exécute et retourne le résultat

        if ($lastPseudo && preg_match('/user(\d{5})/', $lastPseudo, $nb)) { // Forme 'user' suivi de 5 chiffres
            return (int) $nb[1] + 1; // return le nombre recherché + 1
        }

        return 1; // Si aucun pseudo n'est trouvé
    }

    // Recherche en ignorant la casse du pseudo
    public function findOneByLowercasePseudo(string $pseudoLower): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('LOWER(u.pseudo) = :pseudoLower')
            ->setParameter('pseudoLower', $pseudoLower)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
