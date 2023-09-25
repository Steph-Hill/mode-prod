<?php

namespace App\Controller;

use App\Entity\Professional;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, 
                            UserPasswordHasherInterface $userPasswordHasher, 
                            UserAuthenticatorInterface $userAuthenticator, 
                            AppAuthenticator $authenticator, 
                            EntityManagerInterface $entityManager): Response
    {
        // Création d'une nouvelle instance de la classe Professional pour représenter l'utilisateur enregistré
        $user = new Professional();
        
        // Création du formulaire d'enregistrement en utilisant la classe RegistrationFormType et l'entité Professional
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        // Traitement du formulaire lorsqu'il est soumis
        $form->handleRequest($request);
        // Vérification si le formulaire a été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Encodage du mot de passe en utilisant l'interface UserPasswordHasherInterface
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // L'événement de soumission du formulaire déclenche la mise à jour du rôle
            $this->onPreSubmit($user);
            
            // Persistance de l'entité Professional dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();
            
            // Ajouter le message flash "Veuillez vous connecter" après l'enregistrement réussi
            $this->addFlash('success', ' Bienvenue !.');

            // Redirection vers la page de connexion
            return $this->redirectToRoute('app_login');

            // Authentification de l'utilisateur nouvellement enregistrement
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        // Rendu de la vue d'inscription avec le formulaire d'enregistrement
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    private function onPreSubmit(Professional $user): void
    {
        // Récupérer la valeur de hairSalon
        $hairSalon = $user->isHairSalon();

        // Mettre à jour le rôle en fonction de la valeur de hairSalon
        if ($hairSalon === true) {
            $user->setRoles(['ROLE_PROFESSIONAL_SALON']);
        } else {
            $user->setRoles(['ROLE_PROFESSIONAL']);
        }
    }
}
