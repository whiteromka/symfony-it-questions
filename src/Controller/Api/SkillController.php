<?php

namespace App\Controller\Api;

use App\Dto\Input\SkillRequestDto;
use App\Entity\Skill;
use App\Service\SkillService;
use App\Validator\ValidateCsrfToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/skill')]
class SkillController extends BaseController
{
    public function __construct(
        private readonly SkillService $skillService,
        SerializerInterface           $serializer
    ) {
        parent::__construct($serializer);
    }

    #[Route('/get-all', name: 'api_skill_all', methods: ['GET'], format: 'json')]
    public function all(): JsonResponse
    {
        $skills = $this->skillService->findAll();
        $data = array_map(fn($skill) => $this->entityToArray($skill), $skills);
        return $this->setJsonResponse(true, [], $data);
    }

    #[Route('/create', name: 'api_skill_new', methods: ['POST'], format: 'json')]
    #[ValidateCsrfToken]
    public function create(#[MapRequestPayload] SkillRequestDto $skillRequestDto): JsonResponse
    {
        $skill = $this->skillService->createSkill($skillRequestDto);
        $data = $this->entityToArray($skill);
        return $this->setJsonResponse(true, [], $data, 201);
    }

    #[Route('/delete/{name}', name: 'api_skill_delete', requirements: ['name' => '.+'],  methods: ['POST'], format: 'json')]
    #[ValidateCsrfToken]
    public function delete(Skill $skill): JsonResponse
    {
        if (!$skill) {
            return $this->setJsonResponse(false, ['Навык не найден'], [], 404);
        }
        $this->skillService->deleteSkill($skill);
        return $this->setJsonResponse();
    }

    private function entityToArray(Skill $skill): array
    {
        return [
            'id' => $skill->getId(),
            'name' => $skill->getName(),
            'descr' => $skill->getDescr(),
        ];
    }
}
