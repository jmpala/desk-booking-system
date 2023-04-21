<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\UserInterface;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
    )
    {
    }

    public function sendPasswordChanged(UserInterface $user): void
    {
        $this->sendEmail(
            $user,
            'Your password change request',
            '_templates/emails/user_panel/password_changed.html.twig',
        );
    }

    public function sendEmailChanged(UserInterface $user)
    {
        $this->sendEmail(
            $user,
            'Your password change request',
            '_templates/emails/user_panel/email_changed.html.twig',
        );
    }

    private function sendEmail(
        UserInterface $user,
        string        $subject,
        string        $templatePath,
    )
    {
        $email = (new TemplatedEmail())
            ->from(
                new Address(
                    'desk-sharing-app@outlook.de',
                    'Desk App'
                ),
            )
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate($templatePath);
        $this->mailer->send($email);
    }

    public function sendAccountDeleted(UserInterface $user)
    {
        $this->sendEmail(
            $user,
            'Your account has been deleted',
            '_templates/emails/user_panel/account_deleted.html.twig',
        );
    }
}