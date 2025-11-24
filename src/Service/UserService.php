<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
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

    public function newUser(
        string $email,
        string $firstName = '',
        string $lastName = '',
        int $status = 1,
        string $phone = '',
        string $password = '',
    ): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setName($firstName ?? 'Unknown');
        $user->setLastName($lastName ?? 'User');
        $hashedPassword = $this->passwordHasher->hashPassword($user, (!empty($password) ? $password : getenv('DEFAULT_USER_PASSWORD_HASH')));
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);
        $user->setStatus($status);
        $user->setPhone($phone);
        return $user;
    }
}