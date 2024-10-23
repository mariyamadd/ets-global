<?php

namespace App\Controller;

use App\Service\JwtAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class AuthController extends AbstractController
{
    private $jwtAuthenticator;
    private $security;

    public function __construct(JwtAuthenticator $jwtAuthenticator, Security $security)
    {
        $this->jwtAuthenticator = $jwtAuthenticator;
        $this->security = $security;
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(['message' => 'Email and password are required'], 400);
        }

        try {
            $token = $this->jwtAuthenticator->authenticateUser($data['email'], $data['password']);
            return $this->json(['token' => $token]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 401);
        }
    }

    #[Route('/api/verify', name: 'api_verify', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')] 
    public function verify(): JsonResponse
    {
        /** @var \App\Document\User $user */
        $user = $this->security->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        return new JsonResponse([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ], 200);
    }
}

