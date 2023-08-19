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

    private EntityManagerInterface $em;
    private UrlGeneratorInterface $generatorUrl;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $generatorUrl)
    {
        $this->em = $em;
        $this->generatorUrl = $generatorUrl;
    }

    #[Route('/order/create-session-stripe/{reference}', name: 'payment_stripe')]
    public function stripeCheckout($reference): RedirectResponse
    {
        /* j'initialise la variable pour mettre sous format de tableau */
        $productStripe = [];

        $order = $this->em->getRepository(Order::class)->findOneBy([
            'reference' => $reference
        ]);

        

        if (!$order) {

            return $this->redirectToRoute('cart_index');
        }
        /* on recupere tout les produit qui sont choisit dans la commande */
        foreach ($order->getRecapDetails()->getValues() as $product) {
            /* on recupere le produit par son nom */
            $productData = $this->em->getRepository(Product::class)->findOneBy(['name' => $product->getProduct()]);
            
            $productStripe[] = [
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => intval($productData->getPrice()* 1),
                    'product_data' => [
                        'name' => $product->getProduct()
                    ]
                    ],
                'quantity' => $product->getQuantity(),
            ];
        }

        /* On affiche le transporteur par la variable order */
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

        $emailUser = $order->getProfessional()->getEmail();

     

        Stripe::setApiKey('sk_test_51NLKhJA2CMdTOovXm7II093j5PusURYVX2EvKfwLm5yRDu2dhJOhWX4cgGdapwEWQRcYqIEuGZKoIEagvNkCoz1200A5vK1C3L');
         
        $checkout_session = Session::create([
            /* information du produit */
            'customer_email' => $emailUser,
            'payment_method_types' => ['card'],
            'line_items' => [[
                $productStripe
            ]],
            'mode' => 'payment',
            'success_url' => $this->generatorUrl->generate('payment_success',[
                'reference' => $order->getReference(),
            ],UrlGeneratorInterface::ABSOLUTE_URL
        ),
            'cancel_url' => $this->generatorUrl->generate('payment_cancel',[
                'reference' => $order->getReference(),
            ],UrlGeneratorInterface::ABSOLUTE_URL
        ),
            'automatic_tax' => [
                'enabled' => true,
            ],
        ]);

        $order->setStripeSessionId($checkout_session->id);

        $this->em->flush();

        /* envoi a la session de paiment */
        return new RedirectResponse($checkout_session->url);
    }

    /* Route pour le success */
    #[Route('/order/success/{reference}', name: 'payment_success')]
    public function stripeSuccess($reference, CartService $cartService): Response
    {
        return $this->render('order/success.html.twig',[
            'reference' => $reference
        ]);
    }

    /* Route pour l'echec de paiement */
    #[Route('/order/error/{reference}', name: 'payment_cancel')]
    public function stripeCancel($reference, CartService $cartService): Response
    {
        return $this->render('order/cancel.html.twig',[
            'reference' => $reference
        ]);
    }


}