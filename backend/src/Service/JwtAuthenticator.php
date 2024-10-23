<?php

namespace App\Service;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class JwtAuthenticator
{
    private $dm;
    private $passwordHasher;
    private $jwtManager;

    public function __construct(
        DocumentManager $dm,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->dm = $dm;
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
    }

    public function authenticateUser(string $email, string $password): ?string
    {
        // Find the user by email
        $user = $this->dm->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            throw new \Exception('User not found');
        }

        // Validate the password using PasswordHasherInterface
        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new \Exception('Invalid password');
        }

        // Generate JWT token if password is valid
        return $this->jwtManager->create($user);
    }
}
