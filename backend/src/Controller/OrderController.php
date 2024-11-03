<?php

namespace App\Controller;

use App\Dto\OrderDto;
use App\Entity\Order;
use App\Repository\ClientRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/orders')]
class OrderController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private ClientRepository $clientRepository,
        private OrderRepository $orderRepository
    ) {
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $orderDto = $this->serializer->deserialize(
            $request->getContent(),
            OrderDto::class,
            'json'
        );

        $violations = $this->validator->validate($orderDto);
        if (count($violations) > 0) {
            return $this->json(['errors' => $violations], 400);
        }

        $client = $this->clientRepository->find($orderDto->clientId);
        if (!$client) {
            return $this->json(['error' => 'Client not found'], 404);
        }

        $order = new Order();
        $order->setClient($client);
        $order->setOrderType($orderDto->orderType);
        $order->setDescription($orderDto->description);
        $order->setQuantity($orderDto->quantity);
        $order->setStatus($orderDto->status);
        $order->setCreatedBy($this->getUser());

        if ($orderDto->deliveryDate) {
            $order->setDeliveryDate(new \DateTime($orderDto->deliveryDate));
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Order created successfully',
            'order' => $order
        ], 201, [], ['groups' => 'order:read']);
    }

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $orders = $this->orderRepository->findAll();
        return $this->json($orders, 200, [], ['groups' => 'order:read']);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Order $order): JsonResponse
    {
        return $this->json($order, 200, [], ['groups' => 'order:read']);
    }
}