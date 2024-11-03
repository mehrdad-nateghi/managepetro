<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/clients')]
class ClientController extends AbstractController
{
    public function __construct(
        private ClientRepository $clientRepository
    ) {
    }

    #[Route('', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function list(): JsonResponse
    {
        try {
            $user = $this->getUser();
            if (!$user) {
                return $this->json([
                    'message' => 'User not authenticated'
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $clients = $this->clientRepository->findByTenantId($user->getTenantId());

            return $this->json([
                'status' => 'success',
                'data' => $clients
            ], JsonResponse::HTTP_OK, [], ['groups' => ['client:read']]);

        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching clients',
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}