<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|Null find($id)
 * @method Question|Null findOneBy(array $criteria)
 * @method Question[] findAll()
 * @method Question[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method int count(array $criteria)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * Получить рандомный вопрос
     */
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
     * Получить IDs всех вопросов
     *
     * @return array<int> Массив всех ID вопросов
     */
    public function getAllIds(): array
    {
        return $this->createQueryBuilder('q')
            ->select('q.id')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * Получить вопрос по ID
     *
     * @param int $id
     * @return Question|Null
     */
    public function getQuestionById(int $id): ?Question
    {
        return $this->createQueryBuilder('q')
            ->where('q.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Получить список вопросов отсортированных по ID
     *
     * @return array
     */
    public function findAllOrderedById(): array
    {
        return $this->createQueryBuilder('q')
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}