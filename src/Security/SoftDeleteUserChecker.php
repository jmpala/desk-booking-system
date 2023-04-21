<?php

declare(strict_types=1);


namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SoftDeleteUserChecker implements UserCheckerInterface
{

    /**
     * @inheritDoc
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->getDeletedAt() === null) {
            return;
        }

        if ($user->getDeletedAt() < new \DateTime()) {
            throw new UserNotFoundException('User account has been disabled.');
        }
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(UserInterface $user)
    {

    }
}