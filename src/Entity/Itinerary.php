<?php

namespace App\Entity;

use App\Repository\ItineraryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItineraryRepository::class)]
class Itinerary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $itinerary_description = null;

    #[ORM\ManyToOne(inversedBy: 'itinerary')]
    private ?TourPackage $tourPackage = null;

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

    public function getItineraryDescription(): ?string
    {
        return $this->itinerary_description;
    }

    public function setItineraryDescription(string $itinerary_description): static
    {
        $this->itinerary_description = $itinerary_description;

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
}
