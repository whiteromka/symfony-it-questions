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
        private readonly UserService $userService,
        private readonly QuestionCategoryService $questionCategoryService,
        private readonly QuestionHistoryRepository $questionHistoryRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * Получить вопрос
     */
    public function getQuestion(int $id): ?Question
    {
        return $this->questionRepository->find($id);
    }

    /**
     * Получить все вопросы
     */
    public function findAll(): array
    {
        return $this->questionRepository->findAll();
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
        $author = $this->userService->getUserOrFail($questionDto->authorId);

        $question = new Question();
        $question->setTitle($questionDto->title)
            ->setQuestionCategory($category)
            ->setAuthor($author)
            ->setText($questionDto->text ?? '')
            ->setDifficulty($questionDto->difficulty)
            ->setStatus(QuestionStatus::from($questionDto->status));

        $this->entityManager->persist($question);
        $this->entityManager->flush();

        return $question;
    }
}
