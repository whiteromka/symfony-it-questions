<?php

namespace App\Service;

use App\Dto\Input\QuestionRequestDto;
use App\Entity\Question;
use App\Enum\QuestionStatus;
use App\Repository\QuestionHistoryRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class QuestionService
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly QuestionCategoryService $questionCategoryService,
        private readonly QuestionHistoryRepository $questionHistoryRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * Получить рандомный вопрос
     */
    public function getRandomQuestion(): ?Question
    {
        return $this->questionRepository->getRandomQuestion();
    }

    /**
     * Создать запись в истории вопросов
     */
    public function createQuestionHistory(int $questionId): void
    {
        $this->questionHistoryRepository->create($questionId);
    }

    /**
     * @throws Exception
     */
    public function createQuestion(QuestionRequestDto $questionDto): ?Question
    {
        $category = $this->questionCategoryService->getQuestionCategoryOrFail($questionDto->categoryId);

        $question = new Question();
        $question->setTitle($questionDto->title)
            ->setQuestionCategory($category)
            ->setText($questionDto->text)
            ->setDifficulty($questionDto->difficulty)
            ->setStatus(QuestionStatus::from($questionDto->status));

        $this->entityManager->persist($question);
        $this->entityManager->flush();

        return $question;
    }
}