<?php

namespace App\Service;

use App\Dto\Input\SkillRequestDto;
use App\Entity\Skill;
use App\Entity\User;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;

class SkillService
{
    public function __construct(
        private readonly SkillRepository $skillRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {}

    /**
     * @return Skill[]
     */
    public function findAll(): array
    {
        return $this->skillRepository->findAll();
    }

    /**
     * @throws Exception
     */
    public function createSkill(SkillRequestDto $skillDto): ?Skill
    {
        $skill = new Skill();
        $skill->setName($skillDto->name)->setDescr($skillDto->descr ?? '');
        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        return $skill;
    }

    public function deleteSkill(Skill $skill): bool
    {
        $this->entityManager->remove($skill);
        $this->entityManager->flush();
        return true;
    }
}
