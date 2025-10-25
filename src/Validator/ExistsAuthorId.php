<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class ExistsAuthorId extends Constraint
{
    public string $message = 'Не существует автора с таким ID';

    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }
}