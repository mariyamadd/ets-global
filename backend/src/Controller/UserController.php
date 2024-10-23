<?php

namespace App\Controller;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    private $passwordHasher;
    private $dm;

    public function __construct(UserPasswordHasherInterface $passwordHasher, DocumentManager $documentManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->dm = $documentManager;
    }

    #[Route('/api/user', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $data = json_decode($request->getContent(), true);
        
        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']); 
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
    
        $this->dm->persist($user);
        $this->dm->flush();
    
        return new JsonResponse(['message' => 'User registered successfully'], 201);
    }
    

    #[Route('/api/user/{id}', name: 'user_show', methods: ['GET'])]
    public function getAccountUser(string $id): JsonResponse
    {
        $user = $this->dm->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['status' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function updateUser(string $id, Request $request): JsonResponse
    {
        $user = $this->dm->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['status' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $user->setName($data['name']);
        $user->setEmail($data['email']);

        $this->dm->flush();

        return new JsonResponse(['status' => 'User updated'], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteUser(string $id): JsonResponse
    {
        $user = $this->dm->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['status' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->dm->remove($user);
        $this->dm->flush();

        return new JsonResponse(['status' => 'User deleted'], JsonResponse::HTTP_OK);
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login()
    {
        throw new \LogicException('This should not be reached.');
    }
    

}
