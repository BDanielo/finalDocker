<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = (new User())
            ->setFirstname('Daniel')
            ->setLastname('Armieux')
            ->setEmail('admin@test.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(
                $this->hasher->hashPassword(new User(), 'test')
            );

        $post = (new Post())
            ->setTitle('My first post')
            ->setContent('This is my first post')
            ->setEnable(true)
            ->setAuthor($user)
            ->setIp('127.0.0.1');

        $manager->persist($user);
        $manager->persist($post);

        $manager->flush();
    }
}
