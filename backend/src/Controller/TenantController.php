<?php

namespace App\Controller;

use App\Entity\Tenant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tenants', name: 'api_tenants_')]
class TenantController extends AbstractController
{
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Validate required fields
            if (empty($data['companyName']) || empty($data['subscription'])) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Company name and subscription are required'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Validate subscription type
            $validSubscriptions = ['basic', 'premium', 'enterprise'];
            if (!in_array($data['subscription'], $validSubscriptions)) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Invalid subscription type. Must be one of: ' . implode(', ', $validSubscriptions)
                ], Response::HTTP_BAD_REQUEST);
            }

            // Create new tenant
            $tenant = new Tenant();
            $tenant->setCompanyName($data['companyName']);
            $tenant->setSubscription($data['subscription']);

            // Persist and flush
            $entityManager->persist($tenant);
            $entityManager->flush();

            // Return success response
            return $this->json([
                'status' => 'success',
                'message' => 'Tenant created successfully',
                'data' => [
                    'id' => $tenant->getId(),
                    'companyName' => $tenant->getCompanyName(),
                    'subscription' => $tenant->getSubscription()
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the tenant',
                'debug' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}