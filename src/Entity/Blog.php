<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\ManyToOne(targetEntity: BlogCategory::class)]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private BlogCategory|null $blogCategory = null;

    #[ORM\JoinTable(name: 'tag_to_blog')]
    #[ORM\JoinColumn(name: 'blog_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: BTag::class, inversedBy: 'blogs')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getBlogCategory(): ?BlogCategory
    {
        return $this->blogCategory;
    }

    public function setBlogCategory(?BlogCategory $blogCategory): static
    {
        $this->blogCategory = $blogCategory;

        return $this;
    }

    /**
     * @return Collection<int, BTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(BTag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(BTag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
