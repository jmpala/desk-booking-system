<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ChangeEmailFormType;
use App\Form\ChangePasswordFormType;
use App\Form\RequestPasswordFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/user-panel')]
class UserPanelController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private Security    $security,
    )
    {
    }

    #[Route('/', name: 'app_userpanel_showpanel')]
    public function showPanel(): Response
    {
        $form = $this->createForm(
            RequestPasswordFormType::class,
            null,
            [
                'action' => $this->generateUrl('app_userpanel_deleteaccount'),
            ],
        );
        return $this->render(
            'user_panel/index.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }

    #[Route('/change-password', name: 'app_userpanel_changepassword', methods: [
        'GET',
        'POST',
    ])]
    public function changePassword(
        Request                     $request,
        UserPasswordHasherInterface $passwordHasher,
    ): Response
    {
        $form = $this->createForm(
            ChangePasswordFormType::class,
            null,
            [
                'add_current_password' => true,
            ],
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->changePassword(
                $this->getUser(),
                $form->get('plainPassword')
                    ->getData(),
            );
            $this->addFlash(
                'success',
                'Password changed successfully',
            );
            return $this->redirectToRoute('app_userpanel_showpanel');
        }
        return $this->render(
            'user_panel/change_password.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }

    #[Route('/change-email', name: 'app_userpanel_changeemail', methods: [
        'GET',
        'POST',
    ])]
    public function changeEmail(Request $request): Response
    {
        $form = $this->createForm(ChangeEmailFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->changeEmail(
                $this->getUser(),
                $form->get('newEmail')
                    ->getData(),
            );
            $this->addFlash(
                'success',
                'Email changed successfully',
            );
            return $this->redirectToRoute('app_userpanel_showpanel');
        }
        return $this->render(
            'user_panel/change_email.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }

    #[Route('/delete-account', name: 'app_userpanel_deleteaccount', methods: ['POST'])]
    public function deleteAccount(Request $request): Response
    {
        $form = $this->createForm(RequestPasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->deleteAccount($this->getUser());
            $this->security->logout(false);
            if (!$request->hasSession() || !$request->getSession()
                    ->isStarted()) {
                $request->getSession()
                    ->start();
            }
            $this->addFlash(
                'success',
                'Account deleted successfully',
            );
            return $this->redirectToRoute('app_office_overview');
        }
        $this->addFlash(
            'danger',
            'Could not delete account, please contact the administrator',
        );
        return $this->redirectToRoute('app_userpanel_showpanel');
    }
}