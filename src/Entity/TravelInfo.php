<?php

namespace App\Entity;

use App\Repository\TravelInfoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravelInfoRepository::class)]
class TravelInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $travel_info_title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $travel_info_description = null;

    #[ORM\ManyToOne(inversedBy: 'travel_info')]
    private ?TravelInfoCategory $travelInfoCategory = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTravelInfoTitle(): ?string
    {
        return $this->travel_info_title;
    }

    public function setTravelInfoTitle(string $travel_info_title): static
    {
        $this->travel_info_title = $travel_info_title;

        return $this;
    }

    public function getTravelInfoDescription(): ?string
    {
        return $this->travel_info_description;
    }

    public function setTravelInfoDescription(string $travel_info_description): static
    {
        $this->travel_info_description = $travel_info_description;

        return $this;
    }

    public function getTravelInfoCategory(): ?TravelInfoCategory
    {
        return $this->travelInfoCategory;
    }

    public function setTravelInfoCategory(?TravelInfoCategory $travelInfoCategory): static
    {
        $this->travelInfoCategory = $travelInfoCategory;

        return $this;
    }
}
