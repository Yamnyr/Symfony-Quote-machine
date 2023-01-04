<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user1.user@gmail.com');
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);
        $user->setname('user1');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('user2.user@gmail.com');
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);
        $user->setname('user2');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('admin.admin@gmail.com');
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);
        $user->setname('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();

        UserFactory::createMany(5);
        $manager->flush();
    }
}
