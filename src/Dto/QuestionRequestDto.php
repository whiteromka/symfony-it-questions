<?php

namespace App\Dto;

use App\Validator\ContainsCategoryId;
use Symfony\Component\Validator\Constraints as Assert;

class QuestionRequestDto
{
    private const array STATUS_CHOICES = [0, 1];

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $title;

    #[Assert\Type('string')]
    public string $text;

    #[Assert\Type('integer')]
    #[Assert\Range(min: 1, max: 10)]
    public int $difficulty = 5;

    #[Assert\Type('integer')]
    #[Assert\Positive]
    #[ContainsCategoryId]
    public int $categoryId;

    #[Assert\Choice(choices: self::STATUS_CHOICES)]
    public ?int $status = null;
}