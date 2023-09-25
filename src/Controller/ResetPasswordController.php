<?php

namespace App\Controller;

use App\Entity\Professional;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Display & process form to request a password reset.
     */
    #[Route('/reset-password-forget', name: 'app_forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        // Crée un formulaire pour la demande de réinitialisation de mot de passe
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est soumis et valide, envoie un e-mail de réinitialisation de mot de passe
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer,
                $translator
            );
        }
 
        // Affiche le formulaire de demande de réinitialisation de mot de passe
        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     */
    #[Route('/check-email', name: 'app_check_email')]
    public function checkEmail(): Response
    {
        // Génère un jeton factice si l'utilisateur n'existe pas ou si la page est accédée directement
        // Cela évite de révéler si un utilisateur avec l'adresse e-mail existe ou non
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        // Affiche la page de confirmation
        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     */
    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator, string $token = null): Response
    {
        if ($token) {
            // Stocke le jeton en session et redirige vers la même action sans le jeton dans l'URL
            // Cela empêche que le jeton ne soit exposé via l'URL
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            // Si aucun jeton n'est présent, lance une exception
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            // Valide le jeton, récupère l'utilisateur associé
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            // En cas d'erreur de validation de jeton, affiche une erreur et redirige
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // Le jeton est valide, permet à l'utilisateur de changer son mot de passe
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Supprime le jeton après utilisation
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encode le nouveau mot de passe
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            // Met à jour le mot de passe dans la base de données
            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            // Nettoie la session après la réinitialisation du mot de passe
            $this->cleanSessionAfterReset();

            // Redirige vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Affiche le formulaire de réinitialisation du mot de passe
        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse
    {
        // Recherche un utilisateur par adresse e-mail
        $user = $this->entityManager->getRepository(Professional::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Ne révèle pas si un compte utilisateur a été trouvé ou non
        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            // Génère un jeton de réinitialisation de mot de passe
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            // En cas d'erreur, vous pouvez décommenter les lignes ci-dessous pour afficher un message d'erreur
            // Cela peut révéler si un utilisateur est enregistré ou non, donc soyez prudent
            //
            // $this->addFlash('reset_password_error', sprintf(
            //     '%s - %s',
            //     $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
            //     $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            // ));

            return $this->redirectToRoute('app_check_email');
        }

        // Crée un e-mail de réinitialisation de mot de passe et l'envoie
        $email = (new TemplatedEmail())
            ->from(new Address('contact@toplissage.com', 'Top Lissage'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;

        $mailer->send($email);

        // Stocke le jeton en session pour le récupérer dans la route check-email
        $this->setTokenObjectInSession($resetToken);

        // Redirige vers la page de confirmation
        return $this->redirectToRoute('app_check_email');
    }
}
