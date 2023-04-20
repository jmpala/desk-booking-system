<?php

declare(strict_types=1);


namespace App\Controller;

use App\Form\ChangePasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/user-panel')]
class UserPanelController extends AbstractController
{
    #[Route('/', name: 'app_userpanel_showpanel')]
    public function showPanel(): Response
    {
        return $this->render('user_panel/index.html.twig');
    }

    #[Route('/change-password', name: 'app_userpanel_changepassword')]
    public function changePassword(Request $request): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class,
            null,
            [
                'add_current_password' => true,
            ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // validate the old password
            dd('Change password');
        }
        return $this->render('user_panel/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}