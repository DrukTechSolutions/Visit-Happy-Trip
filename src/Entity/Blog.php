<?php

namespace App\Entity;

use App\Repository\BlogRepository;
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
    private ?string $blog_title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $blog_description = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Images $image = null;

    #[ORM\Column(length: 255)]
    private ?string $blog_slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlogTitle(): ?string
    {
        return $this->blog_title;
    }

    public function setBlogTitle(string $blog_title): static
    {
        $this->blog_title = $blog_title;

        return $this;
    }

    public function getBlogDescription(): ?string
    {
        return $this->blog_description;
    }

    public function setBlogDescription(string $blog_description): static
    {
        $this->blog_description = $blog_description;

        return $this;
    }

    public function getImage(): ?Images
    {
        return $this->image;
    }

    public function setImage(?Images $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getBlogSlug(): ?string
    {
        return $this->blog_slug;
    }

    public function setBlogSlug(string $blog_slug): static
    {
        $this->blog_slug = $blog_slug;

        return $this;
    }
}
