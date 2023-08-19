<?php
 
namespace App\Controller;
 
use DateTime;
use App\Entity\Order;
use DateTimeImmutable;
use App\Form\OrderType;
use App\Entity\RecapDetail;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
class OrderController extends AbstractController
{
    private EntityManagerInterface $em;
 
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
 
    // Route pour afficher le formulaire de création de commande
    #[Route('/order/create', name: 'order')]
    public function index(CartService $cartService): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
 
        // Crée le formulaire de commande avec l'utilisateur connecté
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
 
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'recapCart' => $cartService->getTotal()
        ]);
    }
 
    // Route pour préparer la commande
    #[Route('/order/verify', name: 'order_prepare', methods: ['POST'])]
    public function prepareOrder(Request $request, CartService $cartService): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
 
        // Crée le formulaire de commande avec l'utilisateur connecté
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
 
        // Gère la soumission du formulaire
        $form->handleRequest($request);
 
        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $datetime = new DateTimeImmutable('now');
 
            // Récupère les données du transporteur et de la livraison
            $transporter = $form->get('transporter')->getData();
            $delivery = $form->get('addresses')->getData();
 
            // Formatte les informations de livraison pour la commande
            $deliveryForOrder = $delivery->getFirstName() . ' ' . $delivery->getLastName();
            $deliveryForOrder .= '<br>' . $delivery->getPhone();
            if ($delivery->getCompany()) {
                $deliveryForOrder .= ' - ' . $delivery->getCompany();
            }
            $deliveryForOrder .= '<br>' . $delivery->getAddress();
            $deliveryForOrder .= '<br>' . $delivery->getPostalCode() . '-' . $delivery->getCity();
            $deliveryForOrder .= '<br>' . $delivery->getCountry();
 
            // Crée une nouvelle entité Order et définit ses propriétés
            $order = new Order();
            $reference = $datetime->format('Ymd') . '-' . uniqid();
            $order->setProfessional($this->getUser());
            $order->setReference($reference);
            $order->setCreatedAt($datetime);
            $order->setDelivery($deliveryForOrder);
            $order->setTransporterName($transporter->getTitle());
            $order->setTransporteurPrice($transporter->getPrice());
            $order->setIsPaid(0);
            $order->setMethod('stripe');
 
            // Persiste l'entité Order dans l'EntityManager
            $this->em->persist($order);
 
            // Parcours les éléments du panier et crée une entité RecapDetail pour chaque produit
            foreach ($cartService->getTotal() as $product) {
                $recapDetails = new RecapDetail();
                $recapDetails->setOrderProduct($order);
                $recapDetails->setQuantity($product['quantity']);
                $recapDetails->setPrice($product['product']->getPrice());
                $recapDetails->setProduct($product['product']->getName());
                $recapDetails->setTotalRecap($product['product']->getPrice() * $product['quantity']);
 
                // Persiste l'entité RecapDetail dans l'EntityManager
                $this->em->persist($recapDetails);
            }
 
            // Enregistre les entités persistées dans la base de données
            $this->em->flush();
 
            // Renvoie vers la page de récapitulatif de commande
            return $this->render('order/recap.html.twig', [
                'method' => $order->getMethod(),
                'recapCart' => $cartService->getTotal(),
                'transporter' => $transporter,
                'delivery' => $deliveryForOrder,
                'reference' => $order->getReference()
            ]);
        }
 
        // Redirige vers la page du panier si le formulaire n'est pas soumis ou n'est pas valide
        return $this->redirectToRoute('cart_index');
    }
}
