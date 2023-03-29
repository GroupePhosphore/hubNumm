<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_registration', methods: ['POST'])]
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $requestData = json_decode($request->getContent());
        
        $plainPassword = $requestData->password;

        $user = new User();

        $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);

        $user->setUsername($requestData->username)
            ->setPassword($hashedPassword);

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json([
            'status' => 'Success'
        ]);
    }
}
