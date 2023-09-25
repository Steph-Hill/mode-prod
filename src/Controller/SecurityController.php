<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{ 
    

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Vérifie si l'utilisateur est déjà connecté, si oui, redirige vers une autre page (définie par 'target_path')
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // Récupère les éventuelles erreurs de connexion
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Rendu de la vue de connexion avec le dernier nom d'utilisateur et les éventuelles erreurs
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut être laissée vide car elle sera interceptée par la clé 'logout' de votre pare-feu de sécurité.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
