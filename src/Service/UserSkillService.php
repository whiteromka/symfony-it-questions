<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Skill;
use Doctrine\ORM\EntityManagerInterface;

class UserSkillService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @return array []
     */
    public function findAllWithSkills(): array
    {
        return $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->leftJoin('u.skills', 's')
            ->addSelect('s')
            ->getQuery()
            ->getResult();
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
            return false;
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
