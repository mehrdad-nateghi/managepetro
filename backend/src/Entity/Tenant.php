<?php

namespace App\Entity;

use App\Repository\TenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TenantRepository::class)]
class Tenant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $companyName = null;

    #[ORM\Column(length: 50)]
    private ?string $subscription = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'tenant')]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'tenant')]
    private Collection $clients;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'tenant')]
    private Collection $orders;

    #[ORM\OneToMany(targetEntity: DeliveryTruck::class, mappedBy: 'tenant')]
    private Collection $deliveryTrucks;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->deliveryTrucks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function getSubscription(): ?string
    {
        return $this->subscription;
    }

    public function setSubscription(string $subscription): self
    {
        $this->subscription = $subscription;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function getDeliveryTrucks(): Collection
    {
        return $this->deliveryTrucks;
    }
}