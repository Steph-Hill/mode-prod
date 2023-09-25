<?php

namespace App\Controller;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\AddressType; // Importez le formulaire AddressType

class AddressController extends AbstractController
{
    #[Route('/address', name: 'app_address', methods: ['GET', 'POST'])]
    public function createAddress(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        // Crée une nouvelle instance Address
        $address = new Address();

        // Crée le formulaire en utilisant AddressType
        $form = $this->createForm(AddressType::class, $address);

        // Gère la requête
        $form->handleRequest($request);

        // Traite la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Définit la relation entre Address et Professional (utilisateur connecté)
            $address->setProfessional($this->getUser());

            // Persiste les données du formulaire dans la base de données
            $em->persist($address);
            $em->flush();

            // Ajoute un message flash pour indiquer que l'adresse a été ajoutée avec succès
            $this->addFlash(
                'success',
                'Votre adresse de livraison a bien été ajoutée !'
            );


            // Redirige vers la route 'order' ou une autre route appropriée
            return $this->redirectToRoute('order_now'); // Assurez-vous que la route est correcte
        }

        return $this->render('address/index.html.twig', [
            'controller_name' => 'AddressController',
            'form' => $form->createView()
        ]);
    }
}
