<?php

namespace App\Service;

use App\Entity\Question;
use App\Repository\QuestionHistoryRepository;
use App\Repository\QuestionRepository;

class QuestionService
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly QuestionHistoryRepository $questionHistoryRepository
    )
    {
    }

    public function getRandomQuestion(): ?Question
    {
        return $this->questionRepository->getRandomQuestion();
    }

    public function addQuestionHistory(int $questionId): void
    {
        $this->questionHistoryRepository->add($questionId);
    }
}