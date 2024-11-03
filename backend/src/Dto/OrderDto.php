<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OrderDto
{
    #[Assert\NotBlank]
    public ?int $clientId = null;

    #[Assert\NotBlank]
    public ?string $orderType = null;

    public ?string $description = null;

    #[Assert\NotBlank]
    public ?float $quantity = null;

    public ?string $deliveryDate = null;

    public ?string $status = 'PENDING';
}