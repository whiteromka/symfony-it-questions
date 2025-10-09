<?php

namespace App\Service;

use App\Entity\QuestionCategory;
use App\Repository\QuestionCategoryRepository;
use Exception;

class QuestionCategoryService
{
    public function __construct(
        private readonly QuestionCategoryRepository $questionCategoryRepository
    )
    {
    }

    /**
     * Вернет сущность категории вопроса
     */
    public function getQuestionCategoryOrFail(int $categoryId): QuestionCategory
    {
        $category = $this->questionCategoryRepository->find($categoryId);
        if (!$category) {
            throw new Exception("Категория с ID $categoryId не найдена");
        }
        return $category;
    }
}