<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\HairSalon;
use App\Form\HairSalonType;
use App\Entity\Professional;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HairSalonController extends AbstractController
{

    #[Route('/ajout_salon', name: 'app_hair_salon', methods: ['GET', 'POST'])]
    public function createSalon(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response {
 
        if (!$this->isGranted('ROLE_PROFESSIONAL_SALON')) {
            // Rediriger vers une autre route ou une autre URL
            return new RedirectResponse($this->generateUrl('search'));
        }

        // Créer une instance de l'entité HairSalon
        $hairSalon = new HairSalon();

        // Créer le formulaire
        $form = $this->createForm(HairSalonType::class, $hairSalon);

        // Gère la requête du formulaire
        $form->handleRequest($request);


        // Traiter la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            $hairSalon->setCreatedAt(new DateTimeImmutable());

            # Set de la relation entre Article et User
            $hairSalon->setProfessional($this->getUser());

            // Persiste les données du formulaire dans la base de données
            $em->persist($hairSalon);
            $em->flush();

            // Ajoute un message flash pour indiquer que le message a été envoyé avec succès
            $this->addFlash(
                'success',
                'Votre Salon a bien été ajouté !'
            );

            // Redirige vers la route 'app_contact'
            return $this->redirectToRoute('app_hair_salon');
        }


        $professional = $this->getUser();


        $salon = $professional->getHairSalons();


        // Paginer les résultats
         $pagination = $paginator->paginate(
            $salon,
            $request->query->getInt('page', 1), // Numéro de page
            6 // Nombre d'éléments par page
        );
        return $this->render('hair_salon/index.html.twig', [
            'form' => $form->createView(),
            'salon' => $salon,
            'pagination' => $pagination
         ]);
    }

    #[Route('/edit_salon/{id}', name: 'app_hair_salon_edit', methods: ['GET', 'POST'])]
    public function editSalon(Request $request, EntityManagerInterface $em, $id): Response
    {
        
        $salon = $em->getRepository(HairSalon::class)->find($id);

    if (!$salon) {
            throw $this->createNotFoundException('Salon de coiffure non trouvé');
        }
        //Création de mon formulaire
        $form = $this->createForm(HairSalonType::class, $salon);
    
        //Gestion de la requête 
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $salon->setUpdatedAt(new DateTimeImmutable());
            $em->flush();
    
            $this->addFlash('success', 'Salon de coiffure mis à jour avec succès.');
    
            return $this->redirectToRoute('app_hair_salon'); // Redirigez où vous le souhaitez
        }
    
          
        return $this->render('hair_salon/edit.html.twig', [
            'form' => $form->createView(),
            'salon' => $salon,
        ]);
    }

    #[Route('/supprimer_salon/{id}', name: 'app_hair_salon_delete')]
    public function deleteSalon($id, EntityManagerInterface $em): Response
    {
        $salon = $em->getRepository(HairSalon::class)->find($id);

        if (!$salon) {
            throw $this->createNotFoundException('Salon de coiffure non trouvé');
        }

        // Supprimez l'entité de la base de données
        $em->remove($salon);
        $em->flush();

        $this->addFlash('success', 'Salon de coiffure supprimé définitivement.');

        return $this->redirectToRoute('app_hair_salon');
    }

    
}
