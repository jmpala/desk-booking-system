<?php

declare(strict_types=1);

namespace App\service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

class UserService
{
    private int $usersPerPage = 10;

    public function __construct(
        private UserRepository $userRepository
    ){}

    /**
     * Gets all the users from the database and returns a pagerfanta object,
     * ordered by the given page number, column and order, obtained from the request
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function getAllUsers(Request $request) : Pagerfanta
    {
        $page = (int) $request->query->get('page') ?: 1;
        $col = $request->query->get('col') ?: 'email';
        $order = $request->query->get('ord') ?: 'asc';

        $pagerFanta = $this->userRepository->findAllUsersByPageAndOrder($col, $order);
        $pagerFanta->setMaxPerPage($this->usersPerPage);
        $pagerFanta->setCurrentPage($page);

        return $pagerFanta;
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