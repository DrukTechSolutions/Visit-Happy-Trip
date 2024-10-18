<?php

namespace App\Entity;

use App\Repository\HotelsInBhutanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelsInBhutanRepository::class)]
class HotelsInBhutan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $hotel_name = null;

    #[ORM\Column(length: 255)]
    private ?string $ratings = null;

    #[ORM\Column(length: 255)]
    private ?string $room_type = null;

    #[ORM\Column(length: 255)]
    private ?string $no_of_rooms = null;

    /**
     * @var Collection<int, Images>
     */
    #[ORM\OneToMany(targetEntity: Images::class, mappedBy: 'hotelsInBhutan')]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHotelName(): ?string
    {
        return $this->hotel_name;
    }

    public function setHotelName(string $hotel_name): static
    {
        $this->hotel_name = $hotel_name;

        return $this;
    }

    public function getRatings(): ?string
    {
        return $this->ratings;
    }

    public function setRatings(string $ratings): static
    {
        $this->ratings = $ratings;

        return $this;
    }

    public function getRoomType(): ?string
    {
        return $this->room_type;
    }

    public function setRoomType(string $room_type): static
    {
        $this->room_type = $room_type;

        return $this;
    }

    public function getNoOfRooms(): ?string
    {
        return $this->no_of_rooms;
    }

    public function setNoOfRooms(string $no_of_rooms): static
    {
        $this->no_of_rooms = $no_of_rooms;

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
            $image->setHotelsInBhutan($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getHotelsInBhutan() === $this) {
                $image->setHotelsInBhutan(null);
            }
        }

        return $this;
    }
}
