<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\DeleteUserFormType;
use App\Form\UserFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class UserAccountController extends AbstractController
{
    public function __construct(
        private UserService $usersService,
    ) {
    }

    #[Route('/admin/users', name: 'app_usersmanagement_showuserspage')]
    public function listAllPaged(): Response
    {
        return $this->render(
            'admin/users/list.html.twig',
            [
                'pager' => $this->usersService->getAllUsersPaged(),
                'form' => $this->createForm(
                    DeleteUserFormType::class,
                    new User(),
                ),
            ],
        );
    }

    #[Route('/admin/users/create', name: 'app_usersmanagement_createuser')]
    public function showCreate(Request $request): Response
    {
        $form = $this->createForm(
            UserFormType::class,
            new User(),
            ['roles_choices' => $this->getParameter('security.role_hierarchy.roles'),],
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->usersService->createUser($data);
            $this->addFlash(
                'success',
                'User created!',
            );
            return $this->redirectToRoute('app_usersmanagement_showuserspage');
        }

        return $this->render(
            'admin/users/create.html.twig',
            [
                'form' => $form,
            ],
        );
    }

    #[Route('/admin/users/{id}/delete', name: 'app_usersmanagement_deleteuser')]
    public function delete(int $id): Response
    {
        $this->usersService->deleteUser($id);
        $this->addFlash(
            'success',
            'User deleted!',
        );
        return $this->redirectToRoute('app_usersmanagement_showuserspage');
    }

    #[Route('/admin/users/{id}/edit', name: 'app_usersmanagement_edituser')]
    public function showEdit(
        Request $request,
        User $user,
    ): Response {
        $form = $this->createForm(
            UserFormType::class,
            $user,
            [
                'roles_choices' => $this->getParameter('security.role_hierarchy.roles'),
            ],
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->usersService->editUser($data);
            $this->addFlash(
                'success',
                'User edited!',
            );
            return $this->redirectToRoute('app_usersmanagement_showuserspage');
        }

        return $this->render(
            'admin/users/edit.html.twig',
            [
                'form' => $form,
            ],
        );
    }
}