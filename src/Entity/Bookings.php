<?php

namespace App\Entity;

use App\Repository\BookingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingsRepository::class)]
class Bookings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $contact_no = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_of_arrival = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_of_departure = null;

    #[ORM\Column(length: 255)]
    private ?string $no_of_adults = null;

    #[ORM\Column(length: 255)]
    private ?string $no_of_child = null;

    #[ORM\Column(length: 255)]
    private ?string $tour_type = null;

    #[ORM\Column(length: 255)]
    private ?string $tour_packages = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getContactNo(): ?string
    {
        return $this->contact_no;
    }

    public function setContactNo(string $contact_no): static
    {
        $this->contact_no = $contact_no;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getDateOfArrival(): ?\DateTimeInterface
    {
        return $this->date_of_arrival;
    }

    public function setDateOfArrival(\DateTimeInterface $date_of_arrival): static
    {
        $this->date_of_arrival = $date_of_arrival;

        return $this;
    }

    public function getDateOfDeparture(): ?\DateTimeInterface
    {
        return $this->date_of_departure;
    }

    public function setDateOfDeparture(\DateTimeInterface $date_of_departure): static
    {
        $this->date_of_departure = $date_of_departure;

        return $this;
    }

    public function getNoOfAdults(): ?string
    {
        return $this->no_of_adults;
    }

    public function setNoOfAdults(string $no_of_adults): static
    {
        $this->no_of_adults = $no_of_adults;

        return $this;
    }

    public function getNoOfChild(): ?string
    {
        return $this->no_of_child;
    }

    public function setNoOfChild(string $no_of_child): static
    {
        $this->no_of_child = $no_of_child;

        return $this;
    }

    public function getTourType(): ?string
    {
        return $this->tour_type;
    }

    public function setTourType(string $tour_type): static
    {
        $this->tour_type = $tour_type;

        return $this;
    }

    public function getTourPackages(): ?string
    {
        return $this->tour_packages;
    }

    public function setTourPackages(string $tour_packages): static
    {
        $this->tour_packages = $tour_packages;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
