<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// replaces AdvancedUserInterface::isEnabled(): users with is_active = 0 cannot log in
class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user instanceof User && !$user->isEnabled()) {
            throw new DisabledException('Account is disabled.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
