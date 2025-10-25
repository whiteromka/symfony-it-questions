<?php

namespace App\Dto\Input;

use App\Validator\ContainsCategoryId;
use App\Validator\ExistsAuthorId;
use Symfony\Component\Validator\Constraints as Assert;

class QuestionRequestDto
{
    private const array STATUS_CHOICES = [0, 1];

    #[Assert\NotBlank(message: 'Заголовок не может быть пустым')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Заголовок должен быть длиннее {{ limit }} символов',
        maxMessage: 'Заголовок не может быть короче {{ limit }} символов'
    )]
    public string $title;

    #[Assert\NotBlank(message: 'Текст не может быть пустым')]
    #[Assert\Type('string')]
    public string $text;

    #[Assert\Type('integer')]
    #[Assert\Range(min: 1, max: 10)]
    public int $difficulty = 5;

    #[Assert\NotBlank(message: 'ID категории не может быть пустым')]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    #[ContainsCategoryId]
    public int $categoryId;

    #[Assert\NotBlank(message: 'ID автора не может быть пустым')]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    #[ExistsAuthorId]
    public int $authorId;

    #[Assert\Choice(choices: self::STATUS_CHOICES, message: 'Выбран недопустимый статус. Допустимые значения: {{ choices }}')]
    public ?int $status = null;
}