<?php

namespace App\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityIdTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private string $entityClass
    ) {}

    /**
     * Преобразует ID в объект для отображения в форме
     */
    public function transform($id): mixed
    {
        if ($id === null || $id === '') {
            return null;
        }

        // Если пришел уже объект из DTO, возвращаем как есть
        if (is_object($id)) {
            return $id;
        }

        // Если пришел ID, ищем объект для формы
        $entity = $this->entityManager->getRepository($this->entityClass)->find($id);
        if (null === $entity) {
            throw new TransformationFailedException("Сущность {$this->entityClass} с ID $id не существует");
        }
        return $entity;
    }

    /**
     * Преобразует объект в ID для сохранения в DTO
     */
    public function reverseTransform($entity): mixed
    {
        if (null === $entity) {
            return null;
        }

        // Если пришел ID из формы, возвращаем как есть
        if (is_numeric($entity)) {
            return $entity;
        }

        // Если пришел объект, возвращаем его ID
        if (is_object($entity) && method_exists($entity, 'getId')) {
            return $entity->getId();
        }

        return $entity;
    }
}