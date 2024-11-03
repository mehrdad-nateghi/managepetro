<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * @param int $tenantId
     * @return Client[]
     */
    public function findByTenantId(int $tenantId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.tenantId = :tenantId')
            ->setParameter('tenantId', $tenantId)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}