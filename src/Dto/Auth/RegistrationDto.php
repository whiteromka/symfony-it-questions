<?php

namespace App\Dto\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationDto
{
    #[Assert\NotBlank(message: 'Пожалуйста, введите ваше имя')]
    #[Assert\Length(min: 2, max: 50,
        minMessage: 'Имя должно содержать не менее {{ limit }} символов',
        maxMessage: 'Имя не должно быть длиннее {{ limit }} символов'
    )]
    public ?string $name = null;

    #[Assert\Length(min: 2, max: 50,
        minMessage: 'Фамилия должна содержать не менее {{ limit }} символов',
        maxMessage: 'Фамилия не должна быть длиннее {{ limit }} символов'
    )]
    public ?string $lastName = null;

    #[Assert\NotBlank(message: 'Пожалуйста, введите ваш email')]
    #[Assert\Email(message: 'Пожалуйста, введите корректный email адрес')]
    public ?string $email = null;

    #[Assert\Regex(
        pattern: '/^\+?[0-9\s\-\(\)]{10,}$/',
        message: 'Пожалуйста, введите корректный номер телефона'
    )]
    public ?string $phone = null;

    #[Assert\NotBlank(message: 'Пожалуйста, введите пароль')]
    #[Assert\Length(min: 3, max: 10,
        minMessage: 'Пароль должен содержать не менее {{ limit }} символов',
        maxMessage: 'Пароль не должен быть длиннее {{ limit }} символов'
    )]
    public ?string $plainPassword = null;
}