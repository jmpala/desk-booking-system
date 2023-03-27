<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;
use Pagerfanta\Pagerfanta;

class UserService
{
    private int $usersPerPage = 10;

    public function __construct(
        private UserRepository $userRepository,
        private PagerService $pagerService,
    ){}

    /**
     * Gets all the users from the database and returns a pagerfanta object,
     * ordered by the given page number, column and order, obtained from the request
     *
     * @param int    $pageNum
     * @param string $col
     * @param string $order
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function getAllUsersPaged(int $pageNum, string $col, string $order) : Pagerfanta
    {
        return $this->pagerService->createAndConfigurePager(
            $this->userRepository->findAllUsersOrderedQueryBuilder($col, $order),
            $pageNum
        );
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new EntityNotFoundException("User id: {$id} not found");
        }
        $this->userRepository->remove($user, true);
    }

    public function createUser(User $data): void
    {
        $data->setPassword(password_hash('Test@user_123', PASSWORD_DEFAULT));
        $this->userRepository->save($data, true);
    }

    public function editUser(mixed $data): void
    {
        $this->userRepository->save($data, true);
    }
}