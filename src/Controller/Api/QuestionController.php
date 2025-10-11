<?php

namespace App\Controller\Api;

use App\Dto\Input\QuestionRequestDto;
use App\Service\QuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/question')]
class QuestionController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    )
    {
    }

    #[Route(path: '/create', name: 'api_question_create', methods: ['POST'], format: 'json')]
    public function create(
        QuestionService $questionService,
        #[MapRequestPayload] QuestionRequestDto $questionRequestDto
    ): JsonResponse
    {
        $question = $questionService->createQuestion($questionRequestDto);
        $data = $this->serializer->serialize($question, 'json', [
            // В случае циклической рекурсии связанных объектов, просто вернем ID объекта
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId()
        ]);

        return new JsonResponse([
            'success' => true,
            'errors' => [],
            'data' => json_decode($data, true),
        ], 201);
    }
}