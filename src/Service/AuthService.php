<?php

namespace App\Service;

use App\Dto\Auth\RegistrationDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function registerUser(RegistrationDto $registrationDto): User
    {
        $user = new User();
        $user->setName($registrationDto->name);
        $user->setLastName($registrationDto->lastName);
        $user->setEmail($registrationDto->email);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $registrationDto->plainPassword)
        );
        $user->setRoles(['ROLE_USER']);
        $user->setStatus(1);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}