<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    
    // Route pour la page d'accueil par défaut (route '/')
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // Rendu de la vue de la page d'accueil
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    // Route pour la page des mentions légales (route '/mentions-legales')
    #[Route('/mentions-legales', name: 'mentions')]
    public function mentions(): Response
    {
        // Rendu de la vue pour la page des mentions légales
        return $this->render('rgpd/mentions.html.twig');
    }
}
