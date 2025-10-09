<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\QuestionHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionHistory>
 */
class QuestionHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionHistory::class);
    }

    /**
     * Создать запись в истории вопросов
     */
    public function create(int $questionId): ?QuestionHistory
    {
        $question = $this->getEntityManager()->getReference(Question::class, $questionId);

        $questionHistory = new QuestionHistory();
        $questionHistory->setQuestion($question);
        $this->getEntityManager()->persist($questionHistory);
        $this->getEntityManager()->flush();

        return $questionHistory;
    }

    //    /**
    //     * @return QuestionHistory[] Returns an array of QuestionHistory objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?QuestionHistory
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
