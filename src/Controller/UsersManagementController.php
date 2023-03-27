<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Form\DeleteUserFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class UsersManagementController extends AbstractController
{
    public function __construct(
        private UserService $usersService
    ){}

    #[Route('/admin/users', name: 'app_usersmanagement_showuserspage')]
    public function showUsersPage(Request $request) : Response
    {
        $page = $request->query->getInt('page', 1);
        $col = $request->query->getAlpha('col', 'email');
        $order = $request->query->getAlpha('ord', 'asc');

        $pagerfanta = $this->usersService->getAllUsersPaged($page, $col, $order);

        $user = new User();
        $form = $this->createForm(DeleteUserFormType::class, $user);

        return $this->render('admin/users/usersManagement.html.twig', [
            'pager' => $pagerfanta,
            'selectedCol' => $request->query->get('col') ?: 'email',
            'selectedOrder' => $request->query->get('ord') ?: 'asc',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/users/delete/{id}', name: 'app_usersmanagement_deleteuser')]
    public function deleteUser(Request $request, int $id) : Response
    {
        $this->usersService->deleteUser($id);
        return $this->redirectToRoute('app_usersmanagement_showuserspage');
    }

    #[Route('/admin/users/create', name: 'app_usersmanagement_createuser')]
    public function createUser(Request $request) : Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user, [
            'roles_choices' => $this->getParameter('security.role_hierarchy.roles'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->usersService->createUser($data);
            return $this->redirectToRoute('app_usersmanagement_showuserspage');
        }

        return $this->render('admin/users/new/createUser.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/users/edit/{id}', name: 'app_usersmanagement_edituser')]
    public function editUser(Request $request, User $user): Response
    {
        $form = $this->createForm(UserFormType::class, $user, [
            'roles_choices' => $this->getParameter('security.role_hierarchy.roles'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->usersService->editUser($data);
            return $this->redirectToRoute('app_usersmanagement_showuserspage');
        }

        return $this->render('admin/users/edit/editUser.html.twig', [
            'form' => $form,
        ]);
    }
}