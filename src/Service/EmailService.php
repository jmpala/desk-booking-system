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
    ) {
    }

    public function sendPasswordChanged(UserInterface $user): void
    {
        $subject = 'Your password change request';
        $templatePath = '_templates/emails/user_panel/password_changed_email.html.twig';
        $email = (new TemplatedEmail())
            ->from(
                new Address(
                    'desk-sharing-app@outlook.de',
                    'Desk App'
                ),
            )
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate($templatePath)
        ;
        $this->mailer->send($email);
    }
}