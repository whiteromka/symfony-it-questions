<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Attribute;

#[Attribute]
class ContainsCategoryId extends Constraint
{
    public string $message = 'Category ID не существует';

    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }
}
