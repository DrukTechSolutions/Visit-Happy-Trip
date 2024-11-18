<?php

namespace App\Entity;

use App\Repository\HotelsInBhutanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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
    #[ORM\OneToMany(targetEntity: Images::class, mappedBy: 'hotelsInBhutan', cascade : ['persist','remove'])]
    private Collection $images;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $room_details = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ammenities = null;

    #[ORM\Column(length: 255)]
    private ?string $phone_no = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $website = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

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

    public function getRoomDetails(): ?string
    {
        return $this->room_details;
    }

    public function setRoomDetails(string $room_details): static
    {
        $this->room_details = $room_details;

        return $this;
    }

    public function getAmmenities(): ?string
    {
        return $this->ammenities;
    }

    public function setAmmenities(string $ammenities): static
    {
        $this->ammenities = $ammenities;

        return $this;
    }

    public function getPhoneNo(): ?string
    {
        return $this->phone_no;
    }

    public function setPhoneNo(string $phone_no): static
    {
        $this->phone_no = $phone_no;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

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
}
