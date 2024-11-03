<?php
// src/DataFixtures/ClientFixtures.php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $client1 = new Client();
        $client1->setName('Test Client 1');
        $client1->setPhone('1234567890');
        $client1->setAddress('123 Test St');
        $client1->setTenantId(1);
        $manager->persist($client1);

        $client2 = new Client();
        $client2->setName('Test Client 2');
        $client2->setPhone('0987654321');
        $client2->setAddress('456 Test Ave');
        $client2->setTenantId(1);
        $manager->persist($client2);

        $manager->flush();
    }
}