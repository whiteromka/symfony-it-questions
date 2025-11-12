<?php

namespace App\Controller\Api;

use App\Dto\Input\QuestionRequestDto;
use App\Entity\Question;
use App\Service\QuestionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/question')]
class QuestionController extends BaseController
{
    public function __construct(
        private readonly QuestionService $questionService,
        SerializerInterface $serializer
    ) {
        parent::__construct($serializer);
    }

    #[Route(path: '/create', name: 'api_question_create', methods: ['POST'], format: 'json')]
    public function create(#[MapRequestPayload] QuestionRequestDto $questionRequestDto): JsonResponse
    {
        $question = $this->questionService->createQuestion($questionRequestDto);
        $data = $this->entityToArray($question);
        return $this->setJsonResponse(true, [], $data, 201);
    }

    #[Route('/get/{id}', name: 'api_question_get', requirements: ['id' => '\d+'], methods: ['GET'], format: 'json')]
    public function get(Question $question = null): JsonResponse
    {
        if (!$question) {
            return $this->setJsonResponse(false, ['Вопрос не найден'], [], 404);
        }
        $data = $this->entityToArray($question);
        return $this->setJsonResponse(true, [], $data);
    }

    #[Route(path: '/get-all', name: 'api_question_get_all', methods: ['GET'], format: 'json')]
    public function getAll(): JsonResponse
    {
        $questions = $this->questionService->findAll();
        $data = array_map(fn($question) => $this->entityToArray($question), $questions);
        return $this->setJsonResponse(true, [], $data);
    }

    private function entityToArray(Question $question): array
    {
        return [
            'id' => $question->getId(),
            'title' => $question->getTitle(),
            'text' => $question->getText(),
            'difficulty' => $question->getDifficulty(),
            'status' => $question->getStatus()->value,
            'category' => $question->getQuestionCategory() ? [
                'id' => $question->getQuestionCategory()->getId(),
                'name' => $question->getQuestionCategory()->getName(),
            ] : null,
            'createdAt' => $question->getCreatedAt()?->format('Y-m-d H:i:s'),
        ];
    }

}