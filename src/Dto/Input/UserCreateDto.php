<?php

namespace App\Dto\Input;

use Symfony\Component\Validator\Constraints as Assert;

class UserCreateDto
{
    #[Assert\NotBlank(message: 'Имя не может быть пустым')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Имя должно быть не короче {{ limit }} символов',
        maxMessage: 'Имя не может превышать {{ limit }} символов'
    )]
    public string $name;

    #[Assert\Length(
        max: 255,
        maxMessage: 'Фамилия не может превышать {{ limit }} символов'
    )]
    public ?string $lastName = null;

    #[Assert\NotBlank(message: 'Email обязателен')]
    #[Assert\Email(message: 'Некорректный email адрес')]
    #[Assert\Length(max: 255)]
    public string $email;

    #[Assert\Length(
        max: 20,
        maxMessage: 'Телефон не может превышать {{ limit }} символов'
    )]
    public ?string $phone = null;

    #[Assert\Type('integer')]
    #[Assert\Choice([1, 0], message: 'Статус должен быть 1 или 0')]
    public ?int $status = 1;

    #[Assert\Type('array')]
    public array $roles = ['ROLE_USER'];
}
