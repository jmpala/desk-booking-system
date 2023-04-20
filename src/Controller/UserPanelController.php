<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/user-panel')]
class UserPanelController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/', name: 'app_userpanel_showpanel')]
    public function showPanel(): Response
    {
        return $this->render('user_panel/index.html.twig');
    }

    #[Route('/change-password', name: 'app_userpanel_changepassword')]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer,
    ): Response {
        $form = $this->createForm(
            ChangePasswordFormType::class,
            null,
            [
                'add_current_password' => true,
            ],
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')
                    ->getData(),
            );
            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            $email = (new TemplatedEmail())
                ->from(
                    new Address(
                        'desk-sharing-app@outlook.de',
                        'Desk App'
                    ),
                )
                ->to($user->getEmail())
                ->subject('Your password change request')
                ->htmlTemplate('_templates/emails/user_panel/password_changed_email.html.twig')
            ;

            $mailer->send($email);

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
}