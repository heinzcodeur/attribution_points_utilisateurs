<?php

namespace App\Repository;

use App\Data\SearchUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);
        $this->paginator = $paginator;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */

    public function findUsersByPoints()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.totalPoints', 'DESC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findSearchUser(SearchUser $searchUser): PaginationInterface
    {


        $query = $this
            ->createQueryBuilder('u')
            ->select('g', 'u')
            ->join('u.groupe', 'g')
            ->orderBy('u.totalPoints','DESC')
        ;

        if (!empty($searchUser->name)) {
            $query = $query
                ->andWhere(' u.nom LIKE :q or u.prenom LIKE :q')
                ->setParameter('q', "%{$searchUser->name}%");
        }
        if (!empty($searchUser->min)) {
            $query = $query
                ->andWhere('u.totalPoints >= :min')
                ->setParameter('min', $searchUser->min);
        }

        if (!empty($searchUser->max)) {
            $query = $query
                ->andWhere('u.totalPoints <= :max')
                ->setParameter('max', $searchUser->max);
        }

        if (!empty($searchUser->groupe)) {
            $query = $query
                ->andWhere('g.id IN (:groupe)')
                ->setParameter('groupe', $searchUser->groupe);
        }

        return $this->paginator->paginate(
            $query,
            $searchUser->page,
            9
        );
    }



    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
