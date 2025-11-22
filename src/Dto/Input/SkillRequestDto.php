<?php

namespace App\Dto\Input;

use App\Validator\UniqueSkillName;
use Symfony\Component\Validator\Constraints as Assert;

class SkillRequestDto
{
    #[Assert\NotBlank(message: 'Название не может быть пустым')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Заголовок должен быть длиннее {{ limit }} символов',
        maxMessage: 'Заголовок не может быть короче {{ limit }} символов'
    )]
    #[UniqueSkillName]
    public string $name;

    #[Assert\Type('string')]
    #[Assert\Length(
        max: 1000,
        maxMessage: 'Описание не должно превышать {{ limit }} символов'
    )]
    public ?string $descr = null;
}
