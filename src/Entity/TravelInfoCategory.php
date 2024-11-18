<?php

namespace App\Entity;

use App\Repository\TravelInfoCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravelInfoCategoryRepository::class)]
class TravelInfoCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $travel_info_category_name = null;

    /**
     * @var Collection<int, TravelInfo>
     */
    #[ORM\OneToMany(targetEntity: TravelInfo::class, mappedBy: 'travelInfoCategory', fetch: 'EAGER')]
    private Collection $travel_info;

    public function __construct()
    {
        $this->travel_info = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTravelInfoCategoryName(): ?string
    {
        return $this->travel_info_category_name;
    }

    public function setTravelInfoCategoryName(string $travel_info_category_name): static
    {
        $this->travel_info_category_name = $travel_info_category_name;

        return $this;
    }

    /**
     * @return Collection<int, TravelInfo>
     */
    public function getTravelInfo(): Collection
    {
        return $this->travel_info;
    }

    public function addTravelInfo(TravelInfo $travelInfo): static
    {
        if (!$this->travel_info->contains($travelInfo)) {
            $this->travel_info->add($travelInfo);
            $travelInfo->setTravelInfoCategory($this);
        }

        return $this;
    }

    public function removeTravelInfo(TravelInfo $travelInfo): static
    {
        if ($this->travel_info->removeElement($travelInfo)) {
            // set the owning side to null (unless already changed)
            if ($travelInfo->getTravelInfoCategory() === $this) {
                $travelInfo->setTravelInfoCategory(null);
            }
        }

        return $this;
    }
}
