<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Utilities\PagerService;
use Doctrine\ORM\EntityNotFoundException;
use Pagerfanta\Pagerfanta;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private int $usersPerPage = 10;

    public function __construct(
        private UserRepository $userRepository,
        private PagerService $pagerService,
        private UserPasswordHasherInterface $passwordHasher,
        private EmailService $emailService,
    ) {
    }

    /**
     * Gets all the users from the database and returns a pagerfanta object
     *
     * @return \Pagerfanta\Pagerfanta
     * @throws \App\Exception\OutOfIndexPagerException
     */
    public function getAllUsersPaged(): Pagerfanta
    {
        return $this->pagerService->createAndConfigurePager(
            $this->userRepository->findAllUsersOrderedQueryBuilder(),
        );
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new EntityNotFoundException("User id: {$id} not found");
        }
        $this->userRepository->remove(
            $user,
            true,
        );
    }

    public function createUser(User $data): void
    {
        $data->setPassword(
            password_hash(
                'Test@user_123',
                PASSWORD_DEFAULT,
            ),
        );
        $this->userRepository->save(
            $data,
            true,
        );
    }

    public function editUser(mixed $data): void
    {
        $this->userRepository->save(
            $data,
            true,
        );
    }

    public function changePassword(
        User $user,
        string $newPassword,
    ): void {
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $newPassword,
            ),
        );
        $this->userRepository->save(
            $user,
            true,
        );
        $this->emailService->sendPasswordChanged($user);
    }
}