<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create Admin User
        $adminUser = new User();
        $adminUser->setEmail('admin@example.com');
        $adminUser->setName('Admin User');
        $adminUser->setTenantId(1);
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setApiToken('admin_token_' . uniqid());

        $hashedPassword = $this->passwordHasher->hashPassword(
            $adminUser,
            'admin123'
        );
        $adminUser->setPassword($hashedPassword);

        $manager->persist($adminUser);

        // Create Regular User
        $regularUser = new User();
        $regularUser->setEmail('user@example.com');
        $regularUser->setName('Regular User');
        $regularUser->setTenantId(1);
        $regularUser->setRoles(['ROLE_USER']);
        $regularUser->setApiToken('user_token_' . uniqid());

        $hashedPassword = $this->passwordHasher->hashPassword(
            $regularUser,
            'user123'
        );
        $regularUser->setPassword($hashedPassword);

        $manager->persist($regularUser);

        // Create Manager User
        $managerUser = new User();
        $managerUser->setEmail('manager@example.com');
        $managerUser->setName('Manager User');
        $managerUser->setTenantId(1);
        $managerUser->setRoles(['ROLE_MANAGER']);
        $managerUser->setApiToken('manager_token_' . uniqid());

        $hashedPassword = $this->passwordHasher->hashPassword(
            $managerUser,
            'manager123'
        );
        $managerUser->setPassword($hashedPassword);

        $manager->persist($managerUser);

        $manager->flush();
    }
}