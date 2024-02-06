<?php

namespace App\Controller;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\AddressType; 

class AddressController extends AbstractController
{
    #[Route('/address', name: 'app_address', methods: ['GET', 'POST'])]
    public function createAddress(Request $request, EntityManagerInterface $em): Response
    {
        /* Verifie si l'utilisateur est connecté */
        if (!$this->getUser()) {
            /* redirige vers la page de connexion */
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
            //Cette adresse appartient a l'utilisateur
            $address->setProfessional($this->getUser());

            // Persiste les données du formulaire dans la base de données
            $em->persist($address);
            //lance la requete sql par Doctrine pour enregistrer
            $em->flush();

            // Ajoute un message flash pour indiquer que l'adresse a été ajoutée avec succès
            $this->addFlash(
                'success',
                'Votre adresse de livraison a bien été ajoutée !'
            );


            // Redirige vers la route 'order_now'
            return $this->redirectToRoute('order_now'); 
        }

        return $this->render('address/index.html.twig', [
            /* variable pour créer un formulaire */
            'form' => $form->createView()
        ]);
    }
}
