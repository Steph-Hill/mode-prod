<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private RequestStack $requestStack;
    private EntityManagerInterface $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    // Méthode pour démarrer une session
    public function startSession()
    {
        // Récupérer la session à partir de la pile de requêtes
        $start = $this->requestStack->getSession();
    }

    // Méthode pour ajouter un produit au panier
    public function addToCart(int $id): void
    {
        // Récupérer le panier depuis la session ou initialiser un nouveau panier
        $cart = $this->getSession()->get('cart', []);

        // Incrémenter la quantité du produit s'il existe déjà dans le panier, sinon l'ajouter avec une quantité de 1
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        // Mettre à jour le panier dans la session
        $this->getSession()->set('cart', $cart);
    }

    // Méthode pour retirer un produit du panier
    public function removeToCart(int $id)
    {
        // Récupérer le panier depuis la session
        $cart = $this->requestStack->getSession()->get('cart', []);

        // Supprimer le produit du panier
        unset($cart[$id]);

        // Mettre à jour le panier dans la session
        return $this->getSession()->set('cart', $cart);
    }

    // Méthode pour diminuer la quantité d'un produit dans le panier
    public function decrease(int $id)
    {
        // Récupérer le panier depuis la session
        $cart = $this->getSession()->get('cart', []);

        // Décrémenter la quantité du produit s'il est supérieur à 1, sinon le supprimer du panier
        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        // Mettre à jour le panier dans la session
        $this->getSession()->set('cart', $cart);
    }

    // Méthode pour vider complètement le panier
    public function removeCartAll()
    {
        // Supprimer le panier de la session
        return $this->getSession()->remove('cart');
    }

    // Méthode pour obtenir les détails des produits dans le panier
    public function getTotal(): array
    {
        // Récupérer le panier depuis la session
        $cart = $this->getSession()->get('cart');
        $cartData = [];

        // Parcourir chaque produit dans le panier et récupérer les détails du produit depuis la base de données
        if ($cart) {
            foreach ($cart as $id => $quantity) {
                // Rechercher le produit dans la base de données par son identifiant
                $product = $this->em->getRepository(Product::class)->findOneBy(['id' => $id]);
                if (!$product) {
                    // Si le produit n'existe pas, le supprimer du panier
                    $this->removeToCart($id);
                    continue;
                }
                // Ajouter les détails du produit et sa quantité dans le panier à un tableau de données
                $cartData[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }
        // Retourner les détails de tous les produits dans le panier
        return $cartData;
    }

    // Méthode privée pour obtenir la session
    private function getSession(): SessionInterface
    {
        // Récupérer la session à partir de la pile de requêtes
        return $this->requestStack->getSession();
    }
}
