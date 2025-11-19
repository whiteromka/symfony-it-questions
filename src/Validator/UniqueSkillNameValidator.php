<?php

namespace App\Validator;

use App\Entity\Skill;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueSkillNameValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueSkillName) {
            throw new UnexpectedTypeException($constraint, UniqueSkillName::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $existingSkill = $this->entityManager->getRepository(Skill::class)->findOneBy(['name' => $value]);
        if (!empty($existingSkill)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
