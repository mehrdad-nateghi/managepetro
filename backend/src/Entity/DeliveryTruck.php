<?php

// src/Entity/DeliveryTruck.php
namespace App\Entity;

use App\Repository\DeliveryTruckRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryTruckRepository::class)]
class DeliveryTruck
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $plateNumber = null;

    #[ORM\Column]
    private ?float $capacity = null;

    #[ORM\Column(length: 20)]
    private ?string $status = 'available';

    #[ORM\Column]
    private ?int $tenantId = null;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'assignedTruck')]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }
}
