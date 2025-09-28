<?php

namespace App\Controller\Api;

use Exception;
use App\Dto\QuestionRequestDto;
use App\Service\QuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/question')]
class QuestionController extends AbstractController
{
    #[Route(path: '/add-map-request', name: 'api_question_map_request', methods: ['POST'], format: 'json')]
    public function createMapRequest(
        QuestionService $questionService,
        #[MapRequestPayload] QuestionRequestDto $questionRequestDto
    ): JsonResponse
    {
        try {
            $questionService->createQuestion($questionRequestDto);
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