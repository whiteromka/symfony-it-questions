<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserQuestionService
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    /**
     * Найти одного с вопросами
     */
    public function findWithQuestions(int $id): ?User
    {
        return $this->userRepository->findWithQuestions($id);
    }
}
