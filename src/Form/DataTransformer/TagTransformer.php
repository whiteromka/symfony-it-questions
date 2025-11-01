<?php

namespace App\Form\DataTransformer;

use App\Entity\BTag;
use App\Repository\BTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

class TagTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly BTagRepository $tagRepository
    ) {}

    /**
     * Преобразует объекты в массив ID-ов для отображения в форме
     */
    public function transform($value): mixed
    {
        if (null === $value || $value->isEmpty()) {
            return [];
        }

        // Для multiple=true возвращаем массив ID
        return $value->map(fn(BTag $tag) => $tag->getId())->toArray();
    }

    /**
     * Преобразует массив ID-ов из формы в коллекцию объектов
     */
    public function reverseTransform($value): mixed
    {
        if (empty($value)) {
            return new ArrayCollection();
        }

        // Находим теги по ID и возвращаем коллекцию
        $tags = $this->tagRepository->findBy(['id' => $value]);
        return new ArrayCollection($tags);
    }
}