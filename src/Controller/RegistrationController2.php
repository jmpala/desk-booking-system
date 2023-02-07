<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController2 extends AbstractController
{

    #[Route('/register', name: 'app_register_user', methods: ['get'])]
    public function registerUser(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $user->setEmail('test@test.com')
            ->setRoles(['ROLE_USER']);

        $hashedPassword = $passwordHasher->hashPassword($user, 'test@test1');
        $user->setPassword($hashedPassword);

        $repo = $em->getRepository(User::class);
        $repo->save($user);

        $em->flush();

        return $this->redirectToRoute('app_test_url');
    }
}