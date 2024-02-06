<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Product;
use App\Service\CartService;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{

    // Déclaration des propriétés privées pour stocker les instances de l'EntityManager et de l'UrlGeneratorInterface
    private EntityManagerInterface $em;
    private UrlGeneratorInterface $generatorUrl;

    // Constructeur de la classe, prenant en paramètres une instance de l'EntityManager et de l'UrlGeneratorInterface
    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $generatorUrl)
    {
        // Attribution de l'instance de l'EntityManager passée en paramètre à la propriété $em de la classe
        $this->em = $em;

        // Attribution de l'instance de l'UrlGeneratorInterface passée en paramètre à la propriété $generatorUrl de la classe
        $this->generatorUrl = $generatorUrl;
    }

    // Route pour créer une session Stripe et rediriger l'utilisateur vers le formulaire de paiement
    #[Route('/order/create-session-stripe/{reference}', name: 'payment_stripe')]
    public function stripeCheckout($reference): RedirectResponse
    {
        // Initialisation de la variable pour stocker les produits au format tableau
        $productStripe = [];

        // Récupération de la commande associée à la référence
        $order = $this->em->getRepository(Order::class)->findOneBy([
            'reference' => $reference
        ]);

        if (!$order) {
            // Redirection vers le panier si la commande n'est pas trouvée
            return $this->redirectToRoute('cart_index');
        }

        // Récupération des produits choisis dans la commande
        foreach ($order->getRecapDetails()->getValues() as $product) {
            // Récupération du produit par son nom
            $productData = $this->em->getRepository(Product::class)->findOneBy(['name' => $product->getProduct()]);

            // Ajout du produit au tableau pour la session Stripe
            $productStripe[] = [
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => intval($productData->getPrice() * 100),
                    'product_data' => [
                        'name' => $product->getProduct()
                    ]
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        // Ajout du transporteur à la session Stripe
        $productStripe[] = [
            'price_data' => [
                'currency' => 'EUR',
                'unit_amount' => $order->getTransporteurPrice(),
                'product_data' => [
                    'name' => $order->getTransporterName()
                ]
            ],
            'quantity' => 1,
        ];

        // Récupération de l'email de l'utilisateur
        $emailUser = $order->getProfessional()->getEmail();

        // Configuration de l'API Stripe avec la clé secrète
        Stripe::setApiKey('sk_test_...');

        // Création de la session Stripe
        // Création de la session de paiement Stripe en utilisant l'API Stripe
        $checkout_session = Session::create([
            // Adresse e-mail du client associé à cette session
            'customer_email' => $emailUser,
            // Types de méthodes de paiement autorisés (dans ce cas, uniquement la carte de crédit)
            'payment_method_types' => ['card'],
            // Liste des articles ou produits à inclure dans la session de paiement
            'line_items' => $productStripe,
            // Mode de la session de paiement ('payment' pour une session de paiement standard)
            'mode' => 'payment',
            // URL à rediriger en cas de succès du paiement
            'success_url' => $this->generatorUrl->generate('payment_success', [
                'reference' => $order->getReference(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            // URL à rediriger en cas d'annulation du paiement
            'cancel_url' => $this->generatorUrl->generate('payment_cancel', [
                'reference' => $order->getReference(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            // Configuration pour activer le calcul automatique des taxes
            'automatic_tax' => [
                'enabled' => true,
            ],
        ]);


        // Mise à jour de la référence de session Stripe dans la commande
        $order->setStripeSessionId($checkout_session->id);
        $this->em->flush();

        // Redirection vers la session de paiement Stripe
        return new RedirectResponse($checkout_session->url);
    }

    // Route pour la page de succès après un paiement réussi
    #[Route('/order/success/{reference}', name: 'payment_success')]
    public function stripeSuccess($reference, CartService $cartService): Response
    {
        return $this->render('order/success.html.twig', [
            'reference' => $reference
        ]);
    }

    // Route pour la page d'échec de paiement
    #[Route('/order/error/{reference}', name: 'payment_cancel')]
    public function stripeCancel($reference, CartService $cartService): Response
    {
        return $this->render('order/cancel.html.twig', [
            'reference' => $reference
        ]);
    }
}
