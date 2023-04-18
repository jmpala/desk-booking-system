<?php

declare(strict_types=1);


namespace App\Form\Model;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserSelectionModel
{
    #[Assert\NotNull(message: 'Please select a user')]
    public User $user;
}