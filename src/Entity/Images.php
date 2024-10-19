<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $image_name = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?TourPackage $tourPackage = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?HotelsInBhutan $hotelsInBhutan = null;

    #[ORM\ManyToOne(inversedBy: 'image')]
    private ?TopDestination $topDestination = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(string $image_name): static
    {
        $this->image_name = $image_name;

        return $this;
    }

    public function getTourPackage(): ?TourPackage
    {
        return $this->tourPackage;
    }

    public function setTourPackage(?TourPackage $tourPackage): static
    {
        $this->tourPackage = $tourPackage;

        return $this;
    }

    public function getHotelsInBhutan(): ?HotelsInBhutan
    {
        return $this->hotelsInBhutan;
    }

    public function setHotelsInBhutan(?HotelsInBhutan $hotelsInBhutan): static
    {
        $this->hotelsInBhutan = $hotelsInBhutan;

        return $this;
    }

    public function getTopDestination(): ?TopDestination
    {
        return $this->topDestination;
    }

    public function setTopDestination(?TopDestination $topDestination): static
    {
        $this->topDestination = $topDestination;

        return $this;
    }
}
