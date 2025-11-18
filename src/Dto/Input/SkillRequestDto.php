<?php

namespace App\Dto\Input;

use Symfony\Component\Validator\Constraints as Assert;

class SkillRequestDto
{
    #[Assert\NotBlank(message: 'Название не может быть пустым')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Заголовок должен быть длиннее {{ limit }} символов',
        maxMessage: 'Заголовок не может быть короче {{ limit }} символов'
    )]
    public string $name;

    #[Assert\Type('string')]
    public string $descr;
}
