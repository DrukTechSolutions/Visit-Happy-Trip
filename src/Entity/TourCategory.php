<?php

namespace App\Entity;

use App\Repository\TourCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields: ['category'], message : 'This category already exists in the database!')]
#[ORM\Entity(repositoryClass: TourCategoryRepository::class)]
class TourCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $category = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'tourCategories')]
    private ?self $sub_category = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'sub_category')]
    private Collection $tour_categories;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, TourPackage>
     */
    #[ORM\OneToMany(targetEntity: TourPackage::class, mappedBy: 'tourCategory',  fetch: 'EAGER')]
    private Collection $tour_package;

    public function __construct()
    {
        $this->tour_categories = new ArrayCollection();
        $this->tour_package = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getSubCategory(): ?self
    {
        return $this->sub_category;
    }

    public function setSubCategory(?self $sub_category): static
    {
        $this->sub_category = $sub_category;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTourCategories(): Collection
    {
        return $this->tour_categories;
    }

    public function addTourCategory(self $tourCategory): static
    {
        if (!$this->tour_categories->contains($tourCategory)) {
            $this->tour_categories->add($tourCategory);
            $tourCategory->setSubCategory($this);
        }

        return $this;
    }

    public function removeTourCategory(self $tourCategory): static
    {
        if ($this->tour_categories->removeElement($tourCategory)) {
            // set the owning side to null (unless already changed)
            if ($tourCategory->getSubCategory() === $this) {
                $tourCategory->setSubCategory(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, TourPackage>
     */
    public function getTourPackage(): Collection
    {
        return $this->tour_package;
    }

    public function addTourPackage(TourPackage $tourPackage): static
    {
        if (!$this->tour_package->contains($tourPackage)) {
            $this->tour_package->add($tourPackage);
            $tourPackage->setTourCategory($this);
        }

        return $this;
    }

    public function removeTourPackage(TourPackage $tourPackage): static
    {
        if ($this->tour_package->removeElement($tourPackage)) {
            // set the owning side to null (unless already changed)
            if ($tourPackage->getTourCategory() === $this) {
                $tourPackage->setTourCategory(null);
            }
        }

        return $this;
    }
}
