<?php

namespace App\Controller\Api;

use App\Dto\Input\UserCreateDto;
use App\Entity\User;
use App\Entity\Skill;
use App\Service\UserService;
use App\Service\UserSkillService;
use App\Validator\ValidateCsrfToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/user')]
class UserController extends BaseController
{
    public function __construct(
        private readonly UserSkillService $userSkillService,
        private readonly UserService $userService,
        private readonly EntityManagerInterface $entityManager,
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
    public function attachSkill(
        int $userId,
        #[MapEntity(mapping: ['skillId' => 'id'])] Skill $skill
    ): JsonResponse
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
            'skill_name' => $skill->getName(),
            'skill_descr' => $skill->getDescr(),
        ]);
    }

    #[ValidateCsrfToken]
    #[Route('/detach-skill/{userId}/{skillId}',
        name: 'api_user_detach_skill',
        requirements: ['userId' => '\d+', 'skillId' => '\d+'],
        methods: ['POST'],
        format: 'json')]
    public function detachSkill(
        int $userId,
        #[MapEntity(mapping: ['skillId' => 'id'])] Skill $skill
    ): JsonResponse
    {
        $user = $this->userSkillService->findWithSkills($userId);
        if (!$user) {
            return $this->setJsonResponse(false, ['Пользователь не найден'], [], 404);
        }

        $this->userSkillService->detachSkillFromUser($user, $skill);
        $data = $this->entityToArray($user);
        return $this->setJsonResponse(true, [], $data);
    }

    #[Route('/skills/{id}', name: 'api_user_skills',  requirements: ['id' => '\d+'], methods: ['GET'], format: 'json')]
    public function getUserSkills(int $id): JsonResponse
    {
        $user = $this->userSkillService->findWithSkills($id);
        if (!$user) {
            return $this->setJsonResponse(false, ['Пользователь не найден'], [], 404);
        }

        return $this->setJsonResponse(true, [], [
            'user_id' => $user->getId(),
            'skills' => $user->getSkillsAsArray()
        ]);
    }

    #[ValidateCsrfToken]
    #[Route('/create', name: 'api_user_create', methods: ['POST'], format: 'json')]
    public function create(#[MapRequestPayload] UserCreateDto $userCreateDto): JsonResponse
    {
        $user = $this->userService->getUserByEmail($userCreateDto->email);
        if ($user) {
            return $this->setJsonResponse(false, ['Пользователь с таким email уже существует'], [], 200);
        }

        $user = $this->userService->createUser($userCreateDto);
        $data = $this->entityToArray($user);
        return $this->setJsonResponse(true, [], $data, 201);
    }

    #[ValidateCsrfToken]
    #[Route('/delete/{id}', name: 'api_user_delete', methods: ['DELETE'], format: 'json')]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userSkillService->findWithSkills($id);
        if (!$user) {
            return $this->setJsonResponse(false, ['Пользователь не найден'], [], 404);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->setJsonResponse(true, [], []);
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
            'skills' => $user->getSkillsAsArray(),
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}