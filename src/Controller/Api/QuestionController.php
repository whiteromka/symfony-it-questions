<?php

namespace App\Controller\Api;

use App\Dto\Input\QuestionRequestDto;
use App\Service\QuestionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/question')]
class QuestionController extends BaseController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly QuestionService $questionService
    )
    {
    }

    #[Route(path: '/create', name: 'api_question_create', methods: ['POST'], format: 'json')]
    public function create(#[MapRequestPayload] QuestionRequestDto $questionRequestDto): JsonResponse
    {
        $question = $this->questionService->createQuestion($questionRequestDto);
        $data = $this->serializer->serialize($question, 'json', [
            // В случае циклической рекурсии связанных объектов, просто вернем ID объекта
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId()
        ]);
        return $this->setJsonResponse(true, [], json_decode($data, true), 201);
    }

    #[Route(path: '/get/{id}', name: 'api_question_get', methods: ['GET'], format: 'json')]
    public function get(int $id): JsonResponse
    {
        $question = $this->questionService->getQuestion($id);
        if (!$question) {
            return $this->setJsonResponse(false, ['Вопрос не найден'], [], 404);
        }

        $data = $this->serializer->serialize($question, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId()
        ]);
        return $this->setJsonResponse(true, [], json_decode($data, true));
    }
}