<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistsAuthorIdValidator extends ConstraintValidator
{

    public function __construct(private readonly UserRepository $userRepository)
    {}

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$value) {
            $this->context->buildViolation($constraint->message)->addViolation();
            return;
        }

        $author = $this->userRepository->find($value);
        if (!$author) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}