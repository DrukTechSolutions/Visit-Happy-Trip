<?php

namespace App\Entity;

use App\Repository\TourPackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TourPackageRepository::class)]
class TourPackage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tour_category = null;

    #[ORM\Column(length: 255)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $tour_overview = null;

    /**
     * @var Collection<int, Images>
     */
    #[ORM\OneToMany(targetEntity: Images::class, mappedBy: 'tourPackage', cascade : ['persist','remove'])]
    private Collection $images;

    /**
     * @var Collection<int, Itinerary>
     */
    #[ORM\OneToMany(targetEntity: Itinerary::class, mappedBy: 'tourPackage', cascade : ['persist','remove'], orphanRemoval : true)]
    private Collection $itinerary;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->itinerary = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTourCategory(): ?string
    {
        return $this->tour_category;
    }

    public function setTourCategory(string $tour_category): static
    {
        $this->tour_category = $tour_category;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getTourOverview(): ?string
    {
        return $this->tour_overview;
    }

    public function setTourOverview(string $tour_overview): static
    {
        $this->tour_overview = $tour_overview;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setTourPackage($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getTourPackage() === $this) {
                $image->setTourPackage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Itinerary>
     */
    public function getItinerary(): Collection
    {
        return $this->itinerary;
    }

    public function addItinerary(Itinerary $itinerary): static
    {
        if (!$this->itinerary->contains($itinerary)) {
            $this->itinerary->add($itinerary);
            $itinerary->setTourPackage($this);
        }

        return $this;
    }

    public function removeItinerary(Itinerary $itinerary): static
    {
        if ($this->itinerary->removeElement($itinerary)) {
            // set the owning side to null (unless already changed)
            if ($itinerary->getTourPackage() === $this) {
                $itinerary->setTourPackage(null);
            }
        }

        return $this;
    }
}
