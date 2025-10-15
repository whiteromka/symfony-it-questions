<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseController extends AbstractController
{
    public function __construct(
        protected readonly SerializerInterface $serializer
    )
    {
    }

    protected function serializeAndToArray(mixed $data, $format = 'json'): array
    {
        $data = $this->serializer->serialize($data, $format, [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object) => $object->getId()
        ]);
        return json_decode($data, true);
    }

    protected function setJsonResponse(bool $susses = true, array $errors = [], array $data = [], int $statusCode = 200): JsonResponse
    {
        return new JsonResponse([
            'success' => $susses,
            'errors' => $errors,
            'data' => $data,
        ], $statusCode);
    }
}