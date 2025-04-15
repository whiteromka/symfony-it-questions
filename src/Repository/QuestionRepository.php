<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function getRandomQuestion(): ?Question
    {
        $ids = $this->getAllIds();
        if (empty($ids)) {
            return null;
        }

        $randomId = $ids[array_rand($ids)];
        return $this->getQuestionById($randomId);
    }

    /**
     * @return array<int> Массив всех ID вопросов
     */
    public function getAllIds(): array
    {
        return $this->createQueryBuilder('q')
            ->select('q.id')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function getQuestionById(int $id): ?Question
    {
        return $this->createQueryBuilder('q')
            ->where('q.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllOrderedById(): array
    {
        return $this->createQueryBuilder('q')
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}