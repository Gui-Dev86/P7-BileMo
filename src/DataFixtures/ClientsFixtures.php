<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientsFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $client = new Client();
        $client->setUsername('Orange')
            ->setPassword($this->passwordHasher->hashPassword($client, 'azerty'))
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('orange@gmail.fr')
            ->setDateCreate(new \DateTime());
            $this->addReference('0', $client);
        $manager->persist($client);
        
        
        $client = new Client();
        $client->setUsername('SFR')
            ->setPassword($this->passwordHasher->hashPassword($client, 'azerty'))
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('sfr@gmail.fr')
            ->setDateCreate(new \DateTime());
            $this->addReference('1', $client);
        $manager->persist($client);

        
        $client = new Client();
        $client->setUsername('Bouygues')
            ->setPassword($this->passwordHasher->hashPassword($client, 'azerty'))
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('bouygues@gmail.fr')
            ->setDateCreate(new \DateTime());
            $this->addReference('2', $client);
        $manager->persist($client);

        $manager->flush();
    }
}
