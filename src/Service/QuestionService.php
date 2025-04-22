<?php

namespace App\Service;

use App\Dto\QuestionRequestDto;
use App\Entity\Question;
use App\Enum\QuestionStatus;
use App\Repository\QuestionCategoryRepository;
use App\Repository\QuestionHistoryRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class QuestionService
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly QuestionCategoryRepository $questionCategoryRepository,
        private readonly QuestionHistoryRepository $questionHistoryRepository,
        private readonly EntityManagerInterface $entityManager
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

    public function createQuestion(QuestionRequestDto $questionDto): void
    {
        $category = $this->questionCategoryRepository->find($questionDto->categoryId);
        if (!$category) {
            throw new Exception("Нет такой категории");
        }

        $question = new Question();
        $question->setTitle($questionDto->title)
            ->setQuestionCategory($category)
            ->setText($questionDto->text)
            ->setDifficulty($questionDto->difficulty)
            ->setStatus(QuestionStatus::from($questionDto->status));

        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }
}