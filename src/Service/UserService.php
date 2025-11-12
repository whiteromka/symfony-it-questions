<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {}

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * Вернет сущность пользователя
     */
    public function getUserOrFail(int $userId): User
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new Exception("Пользователь с ID $userId не найден");
        }
        return $user;
    }
}