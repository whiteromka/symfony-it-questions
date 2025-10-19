<?php

namespace App\Controller\Api;


use App\Entity\User;
use App\Service\QuestionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/user')]
class UserController extends BaseController
{
    public function __construct(
        private readonly QuestionService $questionService,
        SerializerInterface $serializer
    ) {
        parent::__construct($serializer);
    }

    #[Route('/test', name: 'api_user_test', methods: ['GET'], format: 'json')]
    public function test()
    {
        return new JsonResponse('a');
    }

    #[Route('/get/{id}', name: 'api_user_get', methods: ['GET'], format: 'json')]
    public function get(User $user = null): JsonResponse
    {
        if (!$user) {
            return $this->setJsonResponse(false, ['Пользователь не найден'], [], 404);
        }
        $data = $this->entityToArray($user);
        return $this->setJsonResponse(true, [], $data);
    }

    #[Route('/get-email/{id}', name: 'api_user_get', methods: ['GET'], format: 'json')]
    public function getEmail(User $user = null): JsonResponse
    {
        if (!$user) {
            return $this->setJsonResponse(false, ['Пользователь не найден'], [], 404);
        }
        return $this->setJsonResponse(true, [], ['email' => $user->getEmail()]);
    }

    private function entityToArray(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'status' => $user->getStatus(),
            'phone' => $user->getPhone(),
            'roles' => $user->getRoles(),
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
        ];
    }

}