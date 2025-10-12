<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends AbstractController
{
    protected function setJsonResponse(bool $susses = true, array $errors = [], array $data = [], int $statusCode = 200): JsonResponse
    {
        return new JsonResponse([
            'success' => $susses,
            'errors' => $errors,
            'data' => $data,
        ], $statusCode);
    }
}