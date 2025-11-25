<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Skill;
use App\Service\UserSkillService;
use App\Validator\ValidateCsrfToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/user')]
class UserController extends BaseController
{
    public function __construct(
        private readonly UserSkillService $userSkillService,
        SerializerInterface $serializer
    ) {
        parent::__construct($serializer);
    }

    #[Route('/get-all', name: 'api_user_all', methods: ['GET'], format: 'json')]
    public function all(): JsonResponse
    {
        $users = $this->userSkillService->findAllWithSkills();
        $data = array_map(fn(User $user) => $this->entityToArray($user), $users);
        return $this->setJsonResponse(true, [], $data);
    }

    #[Route('/get/{id}', name: 'api_user_get', requirements: ['id' => '\d+'], defaults: ['id' => 2],  methods: ['GET'], format: 'json')]
    public function get(int $id): JsonResponse
    {
        $user = $this->userSkillService->findWithSkills($id);
        if (!$user) {
            return $this->setJsonResponse(false, ['Пользователь не найден'], [], 404);
        }
        $data = $this->entityToArray($user);
        return $this->setJsonResponse(true, [], $data);
    }

    #[Route('/get-email/{id}', name: 'api_user_get_email',  requirements: ['id' => '\d+'], methods: ['GET'], format: 'json')]
    public function getEmail(int $id): JsonResponse
    {
        $user = $this->userSkillService->findWithSkills($id);
        if (!$user) {
            return $this->setJsonResponse(false, ['Пользователь не найден'], [], 404);
        }
        return $this->setJsonResponse(true, [], ['email' => $user->getEmail()]);
    }

    #[ValidateCsrfToken]
    #[Route('/attach-skill/{userId}/{skillId}',
        name: 'api_user_attach_skill',
        requirements: ['userId' => '\d+', 'skillId' => '\d+'],
        methods: ['POST'],
        format: 'json')]
    public function attachSkill(int $userId, Skill $skill): JsonResponse
    {
        $user = $this->userSkillService->findWithSkills($userId);
        if (!$user) {
            return $this->setJsonResponse(false, ['Пользователь не найден'], [], 404);
        }
        if (!$this->userSkillService->attachSkillToUser($user, $skill)) {
            return $this->setJsonResponse(false, ['Навык уже прикреплен к пользователю'], [], 200);
        }

        return $this->setJsonResponse(true, [], [
            'user_id' => $user->getId(),
            'skill_id' => $skill->getId(),
            'skill_name' => $skill->getName()
        ]);
    }

    #[ValidateCsrfToken]
    #[Route('/detach-skill/{userId}/{skillId}',
        name: 'api_user_detach_skill',
        requirements: ['userId' => '\d+', 'skillId' => '\d+'],
        methods: ['POST'],
        format: 'json')]
    public function detachSkill(int $userId, Skill $skill): JsonResponse
    {
        $user = $this->userSkillService->findWithSkills($userId);
        if (!$user) {
            return $this->setJsonResponse(false, ['Пользователь не найден'], [], 404);
        }
        if (!$this->userSkillService->detachSkillFromUser($user, $skill)) {
            return $this->setJsonResponse(false, ['Навык не прикреплен к пользователю'], [], 200);
        }

        return $this->setJsonResponse(true, [], [
            'user_id' => $user->getId(),
            'skill_id' => $skill->getId(),
            'skill_name' => $skill->getName()
        ]);
    }

    #[Route('/skills/{id}', name: 'api_user_skills',  requirements: ['id' => '\d+'], methods: ['GET'], format: 'json')]
    public function getUserSkills(int $id): JsonResponse
    {
        $user = $this->userSkillService->findWithSkills($id);
        if (!$user) {
            return $this->setJsonResponse(false, ['Пользователь не найден'], [], 404);
        }

        $skills = $user->getSkills()->map(function(Skill $skill) {
            return [
                'id' => $skill->getId(),
                'name' => $skill->getName(),
                'description' => $skill->getDescr()
            ];
        })->toArray();

        return $this->setJsonResponse(true, [], [
            'user_id' => $user->getId(),
            'skills' => $skills
        ]);
    }

    private function entityToArray(User $user): array
    {
        $skills = $user->getSkills()->map(function(Skill $skill) { // Навыки уже загружены жадной загрузкой
            return [
                'id' => $skill->getId(),
                'name' => $skill->getName(),
                'description' => $skill->getDescr()
            ];
        })->toArray();

        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'status' => $user->getStatus(),
            'phone' => $user->getPhone(),
            'roles' => $user->getRoles(),
            'skills' => $skills,
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}