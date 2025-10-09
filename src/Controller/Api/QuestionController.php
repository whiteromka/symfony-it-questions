<?php

namespace App\Controller\Api;

use App\Dto\Input\QuestionRequestDto;
use App\Service\QuestionService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/question')]
class QuestionController extends AbstractController
{
    #[Route(path: '/create', name: 'api_question_create', methods: ['POST'], format: 'json')]
    public function create(
        QuestionService $questionService,
        #[MapRequestPayload] QuestionRequestDto $questionRequestDto
    ): JsonResponse
    {
        try {
            $question = $questionService->createQuestion($questionRequestDto);
            // ToDo возвращать данные сущности
            return $this->json([
                'success' => true,
                'error' => false
            ]);
        } catch (Exception $exception) {
            return $this->json([
                'success' => false,
                'error' => $exception->getMessage()
            ]);
        }
    }
}