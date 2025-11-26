<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Skill;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserSkillService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * Найти всех с навыками
     * @return array []
     */
    public function findAllWithSkills(): array
    {
        return $this->userRepository
            ->createQueryBuilder('u')
            ->leftJoin('u.skills', 's')
            ->addSelect('s')
            ->getQuery()
            ->getResult();
    }

    /**
     * Найти одного с навыками
     */
    public function findWithSkills(int $id): ?User
    {
        return $this->userRepository
            ->createQueryBuilder('u')
            ->leftJoin('u.skills', 's')
            ->addSelect('s')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /** Прикрепить навык к польз-лю */
    public function attachSkillToUser(User $user, Skill $skill): bool
    {
        if ($user->getSkills()->contains($skill)) {
            return false;
        }

        $user->addSkill($skill);
        $this->entityManager->flush();
        return true;
    }

    /** Открепить навык от польз-ля */
    public function detachSkillFromUser(User $user, Skill $skill): bool
    {
        if (!$user->getSkills()->contains($skill)) {
            return true;
        }

        $user->removeSkill($skill);
        $this->entityManager->flush();

        return true;
    }

    /** Пользователь обладает навыком */
    public function hasSkill(User $user, Skill $skill): bool
    {
        return $user->getSkills()->contains($skill);
    }

    /** Получить навыки пол-ля */
    public function getUserSkills(User $user): array
    {
        return $user->getSkills()->toArray();
    }

    /** Прикрепить навыки к пользователю */
    public function attachSkillsToUser(User $user, array $skills): void
    {
        foreach ($skills as $skill) {
            if (!$this->hasSkill($user, $skill)) {
                $user->addSkill($skill);
            }
        }
        $this->entityManager->flush();
    }

    /** Открепить все навыки пользователя */
    public function detachAllSkillsFromUser(User $user): void
    {
        foreach ($user->getSkills() as $skill) {
            $user->removeSkill($skill);
        }
        $this->entityManager->flush();
    }
}
