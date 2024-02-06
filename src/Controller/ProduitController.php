<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    // Route pour afficher la liste des produits
    #[Route('/products', name: 'produits')]
    public function index(EntityManagerInterface $entityManager, 
                        PaginatorInterface  $paginator,
                        Request $request): Response
    {
        // Récupère le référentiel des produits
        $productRepository = $entityManager->getRepository(Product::class);
        
        // Récupère tous les produits
        $products = $productRepository->findAll();

        //variable de la pagination 
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1), // Numéro de page par défaut
            8 // Nombre d'éléments par page
        );

        // Affiche la liste des produits en utilisant un fichier de modèle Twig
        return $this->render('produit/index.html.twig', [
            'products' => $products,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/products/{id}', name: 'produit_details')]
    public function show(Product $product): Response
    {
        // Affiche la page de détails du produit en utilisant un fichier de modèle Twig
        return $this->render('produit/details.html.twig', [
            'product' => $product
        ]);
    }
}
