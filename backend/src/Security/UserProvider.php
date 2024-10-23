<?php

namespace App\Security;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function loadUserByIdentifier(string $email): UserInterface
    {
        $user = $this->dm->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            throw new \Exception(sprintf('Email "%s" does not exist.', $email));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new \Exception('Unsupported user class.');
        }

        return $this->loadUserByIdentifier($user->getEmail());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
