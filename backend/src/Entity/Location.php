<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['location:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['location:read', 'location:write'])]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Groups(['location:read', 'location:write'])]
    private ?string $city = null;

    #[ORM\Column(length: 10)]
    #[Groups(['location:read', 'location:write'])]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['location:read', 'location:write'])]
    private ?string $country = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['location:read', 'location:write'])]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['location:read', 'location:write'])]
    private ?float $longitude = null;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Client::class)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'startLocation', targetEntity: DeliveryTruck::class)]
    private Collection $deliveryTrucks;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['location:read', 'location:write'])]
    private ?string $state = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['location:read', 'location:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['location:read', 'location:write'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->deliveryTrucks = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

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

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setLocation($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getLocation() === $this) {
                $client->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DeliveryTruck>
     */
    public function getDeliveryTrucks(): Collection
    {
        return $this->deliveryTrucks;
    }

    public function addDeliveryTruck(DeliveryTruck $deliveryTruck): static
    {
        if (!$this->deliveryTrucks->contains($deliveryTruck)) {
            $this->deliveryTrucks->add($deliveryTruck);
            $deliveryTruck->setStartLocation($this);
        }

        return $this;
    }

    public function removeDeliveryTruck(DeliveryTruck $deliveryTruck): static
    {
        if ($this->deliveryTrucks->removeElement($deliveryTruck)) {
            // set the owning side to null (unless already changed)
            if ($deliveryTruck->getStartLocation() === $this) {
                $deliveryTruck->setStartLocation(null);
            }
        }

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getFullAddress(): string
    {
        $parts = [
            $this->address,
            $this->city,
            $this->state,
            $this->zipCode,
            $this->country
        ];

        return implode(', ', array_filter($parts));
    }

    public function __toString(): string
    {
        return $this->getFullAddress();
    }
}