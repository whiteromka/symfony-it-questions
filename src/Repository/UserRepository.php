<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|Null find($id)
 * @method User|Null findOneBy(array $criteria)
 * @method User[] findAll()
 * @method User[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method int count(array $criteria)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Найти всех с навыками
     * @return array []
     */
    public function findAllWithSkills(): array
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.skills', 's')
            ->addSelect('s')
            ->getQuery()
            ->getResult();
    }

    /**
     * Найти одного с навыками по ID
     */
    public function findWithSkills(int $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.skills', 's')
            ->addSelect('s')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Найти одного с вопросами по ID
     */
    public function findWithQuestions(int $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.questions', 'q')
            ->addSelect('q')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
