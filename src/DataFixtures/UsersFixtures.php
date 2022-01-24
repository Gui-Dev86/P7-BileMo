<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [];

        for ($i = 1; $i < 50; $i++) {
            
            $user = new User();
            $user->setUsername('username_' . $i)
                ->setPassword($this->passwordHasher->hashPassword($user, 'azerty'))
                ->setRoles(['ROLE_USER'])
                ->setEmail('username_' . $i . '@gmail.fr')
                ->setLastname('lastname_' . $i)
                ->setFirstname('firstname_' . $i)
                ->setDateCreate(new \DateTime())
                ->setClient($this->getReference(rand(0, 2)));

            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();
    }

}
