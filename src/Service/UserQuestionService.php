<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserQuestionService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly QuestionRepository $questionRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * Найти одного с вопросами
     */
    public function findWithQuestions(int $id): ?User
    {
        return $this->userRepository->findWithQuestions($id);
    }
}
