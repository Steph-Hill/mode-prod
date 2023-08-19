<?php
 
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;;
 
class MailerController extends AbstractController
{
    // Route pour envoyer l'e-mail de test
    #[Route('/send-email', name: 'app_send_email')]
    public function index(MailerInterface $mailer): Response
    {
        // Crée une instance de l'e-mail
        $email = (new Email())
            ->from('sample-sender@binaryboxtuts.com')
            ->to('sample-recipient@binaryboxtuts.com')
            ->subject('Email Test')
            ->text('A sample email using mailtrap.');
 
        // Envoie l'e-mail en utilisant le service MailerInterface
        $mailer->send($email);
 
        // Retourne une réponse pour indiquer que l'e-mail a été envoyé avec succès
        return new Response('Email sent successfully');
    }
}
