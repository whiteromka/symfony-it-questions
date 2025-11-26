<?php

namespace App\Service;

use App\Dto\Input\UserCreateDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager
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

    /**
     * Вернет сущность пользователя по email
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    /** Создать нового пользователя */
    public function newUser(
        string $email,
        string $name = '',
        string $lastName = '',
        int    $status = 1,
        string $phone = '',
        string $password = '',
        array $roles = [],
    ): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setName($name ?? 'Unknown');
        $user->setLastName($lastName ?? 'User');
        $hashedPassword = $this->passwordHasher->hashPassword($user, (!empty($password) ? $password : getenv('DEFAULT_USER_PASSWORD_HASH')));
        $user->setPassword($hashedPassword);
        $user->setRoles(!empty($roles) ? $roles : ['ROLE_USER']);
        $user->setStatus($status);
        $user->setPhone($phone);
        return $user;
    }

    /** Создать нового пользователя в БД */
    public function createUser(UserCreateDto $dto): ?User
    {
        $user = $this->newUser($dto->email, $dto->name, $dto->lastName, $dto->status, $dto->phone);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /** Удалить пользователя */
    public function deleteUser(User $user): bool
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return true;
    }
}