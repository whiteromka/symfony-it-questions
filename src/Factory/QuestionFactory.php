<?php

namespace App\Factory;

use App\Dto\Input\QuestionRequestDto;
use App\Entity\Question;
use App\Entity\QuestionCategory;
use App\Entity\User;
use App\Enum\QuestionStatus;
use Doctrine\ORM\EntityManagerInterface;

class QuestionFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function createFromDto(QuestionRequestDto $dto, ?Question $question = null): Question
    {
        if (!$question) {
            $question = new Question();
        }

        $question->setTitle($dto->title);
        $question->setText($dto->text);
        $question->setDifficulty($dto->difficulty);

        // Преобразуем ID в объекты
        if ($dto->categoryId) {
            $category = $this->entityManager->getReference(QuestionCategory::class, $dto->categoryId);
            $question->setQuestionCategory($category);
        }

        if ($dto->authorId) {
            $author = $this->entityManager->getReference(User::class, $dto->authorId);
            $question->setAuthor($author);
        }

        if ($dto->status !== null) {
            $status = QuestionStatus::from($dto->status);
            $question->setStatus($status);
        }

        return $question;
    }

    public function createDtoFromEntity(Question $question): QuestionRequestDto
    {
        $dto = new QuestionRequestDto();
        $dto->title = $question->getTitle();
        $dto->text = $question->getText();
        $dto->difficulty = $question->getDifficulty();
        $dto->categoryId = $question->getQuestionCategory()?->getId();
        $dto->authorId = $question->getAuthor()?->getId();
        $dto->status = $question->getStatus()->value;

        return $dto;
    }
}