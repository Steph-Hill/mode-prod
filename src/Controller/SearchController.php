<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\HairSalonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    // Route pour afficher la page de recherche de salons de coiffure
    #[Route('/search', name: 'search')]
    public function index(
        HairSalonRepository $hairSalonRepository,
        Request $request
    ): Response 
    {
        // Création d'une instance de la classe SearchData pour stocker les données de recherche
        $searchData = new SearchData();

        // Création du formulaire de recherche en utilisant la classe SearchType et les données de $searchData
        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);
        
        // Vérification si le formulaire a été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de la page courante à partir des paramètres de requête (par défaut: page 1)
            $searchData->page = $request->query->getInt('page', 1);
            
            // Recherche des salons de coiffure en fonction des critères de recherche
            $hairSalon = $hairSalonRepository->findByCodePostal($searchData);

            // Rendu de la vue avec le formulaire et les résultats de la recherche
            return $this->render('search/index.html.twig', [
                'form' => $form->createView(),
                'hairSalon' => $hairSalon
            ]);
        }

        // Rendu de la vue avec le formulaire et les salons de coiffure par défaut (sans recherche)
        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
            'hairSalon' => $hairSalonRepository->findByCodePostal($request->query->getInt('page', 1))
        ]);
    }
}
