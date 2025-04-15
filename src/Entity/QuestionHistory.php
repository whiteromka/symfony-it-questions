<?php

namespace App\Entity;

use App\Repository\QuestionHistoryRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: QuestionHistoryRepository::class)]
class QuestionHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Многие QuestionHistory имеют один question
    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[ORM\JoinColumn(name: 'question_id', referencedColumnName: 'id')]
    private Question $question;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $lastTimeShowed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getLastTimeShowed(): ?DateTimeInterface
    {
        return $this->lastTimeShowed;
    }

    public function setLastTimeShowed(DateTimeInterface $lastTimeShowed): static
    {
        $this->lastTimeShowed = $lastTimeShowed;

        return $this;
    }

    #[ORM\PrePersist]
    public function setLastTimeShowedValue(): void
    {
        $this->lastTimeShowed = new DateTimeImmutable();
    }
}
