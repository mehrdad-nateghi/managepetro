<?php

// src/Entity/Order.php
namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')] // using backticks as 'order' is a reserved word
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?float $fuelAmount = null;

    #[ORM\Column(length: 20)]
    private ?string $status = 'pending';

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $deliveryDate = null;

    #[ORM\Column]
    private ?int $tenantId = null;

    #[ORM\ManyToOne(targetEntity: DeliveryTruck::class)]
    private ?DeliveryTruck $assignedTruck = null;
}
