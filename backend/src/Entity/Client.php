<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['client:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['client:read', 'client:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['client:read', 'client:write'])]
    private ?string $address = null;

    #[ORM\Column(length: 20)]
    #[Groups(['client:read', 'client:write'])]
    private ?string $phone = null;

    #[ORM\Column]
    #[Groups(['client:read'])]  // Added serialization group for tenantId
    private ?int $tenantId = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Order::class)]
    #[Groups(['client:read'])]  // Added if you need to access orders in the API
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getTenantId(): ?int
    {
        return $this->tenantId;
    }

    public function setTenantId(int $tenantId): static
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    /*public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;
        return $this;
    }*/

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setClient($this);
        }
        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            if ($order->getClient() === $this) {
                $order->setClient(null);
            }
        }
        return $this;
    }

    /**
     * @return array
     * @Groups({"client:read"})
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'address' => $this->getAddress(),
            'phone' => $this->getPhone(),
            'tenantId' => $this->getTenantId(),
        ];
    }
}