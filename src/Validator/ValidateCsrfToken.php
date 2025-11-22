<?php

namespace App\Validator;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class ValidateCsrfToken
{
    public function __construct(
        public string $tokenId = 'authenticate',
        public string $paramName = '_token'
    ) {}
}
