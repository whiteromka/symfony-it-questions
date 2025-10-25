<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\QuestionCategoryRepository;

class ContainsCategoryIdValidator extends ConstraintValidator
{
    private QuestionCategoryRepository $questionCategoryRepository;

    public function __construct(QuestionCategoryRepository $questionCategoryRepository)
    {
        $this->questionCategoryRepository = $questionCategoryRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }

        $category = $this->questionCategoryRepository->find($value);
        if (!$category) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
