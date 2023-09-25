<?php

namespace App\Mailer;

use Scheb\TwoFactorBundle\Mailer\AuthCodeMailerInterface;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MyAuthCodeMailer implements AuthCodeMailerInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendAuthCode(TwoFactorInterface $user): void
    {
        $authCode = $user->getEmailAuthCode();

        // Créer une instance de l'e-mail à envoyer
        $email = (new TemplatedEmail())
            ->from('contacto@toplissage.com')
            ->to($user->getEmailAuthRecipient())
            ->subject('Code d\'authentification')
            ->htmlTemplate('security/2fa_code_email.html.twig')
            ->context([
                'authCode' => $authCode,
            ]);

        $this->mailer->send($email);
    }
}