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
    // Route pour l'ajout de salons
    #[Route('/ajout_salon', name: 'app_hair_salon', methods: ['GET', 'POST'])]
    public function createSalon(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response {

        // Contrôle d'accès du rôle ROLE_PROFESSIONAL_SALON
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
            // Ajoute les informations nécessaires pour le champ createdAt
            $hairSalon->setCreatedAt(new DateTimeImmutable());

            // Set de la relation entre HairSalon et Professional
            $hairSalon->setProfessional($this->getUser());

            // Persiste les données du formulaire dans la base de données
            $em->persist($hairSalon);
            // On envoie dans la base de données
            $em->flush();

            // Ajoute un message flash pour indiquer que le salon a bien été ajouté
            $this->addFlash(
                'success',
                'Votre Salon a bien été ajouté !'
            );

            // Redirige vers la route 'app_hair_salon'
            return $this->redirectToRoute('app_hair_salon');
        }

        // Récupère le nom de l'utilisateur connecté
        $professional = $this->getUser();

        // Récupère les salons des professionnels
        $salon = $professional->getHairSalons();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $salon,
            $request->query->getInt('page', 1), // Numéro de page
            6 // Nombre d'éléments par page
        );

        // Rendu vers la vue pour afficher les salons
        return $this->render('hair_salon/index.html.twig', [
            'form' => $form->createView(),
            'salon' => $salon,
            'pagination' => $pagination
        ]);
    }

    // Route pour l'édition d'un salon
    #[Route('/edit_salon/{id}', name: 'app_hair_salon_edit', methods: ['GET', 'POST'])]
    public function editSalon(Request $request, EntityManagerInterface $em, $id): Response
    {
        // Récupère le salon à éditer par son ID
        $salon = $em->getRepository(HairSalon::class)->find($id);

        if (!$salon) {
            throw $this->createNotFoundException('Salon de coiffure non trouvé');
        }

        // Création du formulaire
        $form = $this->createForm(HairSalonType::class, $salon);

        // Gestion de la requête
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Met à jour la date de modification
            $salon->setUpdatedAt(new DateTimeImmutable());
            // Enregistre les modifications dans la base de données
            $em->flush();

            // Ajoute un message flash de succès
            $this->addFlash('success', 'Salon de coiffure mis à jour avec succès.');

            // Redirige vers la route 'app_hair_salon'
            return $this->redirectToRoute('app_hair_salon');
        }

        // Rendu de la vue pour l'édition du salon pour le modifier
        return $this->render('hair_salon/edit.html.twig', [
            'form' => $form->createView(),
            'salon' => $salon,
        ]);
    }

    // Route pour la suppression d'un salon
    #[Route('/supprimer_salon/{id}', name: 'app_hair_salon_delete')]
    public function deleteSalon($id, EntityManagerInterface $em): Response
    {
        // Récupère le salon à supprimer par son ID
        $salon = $em->getRepository(HairSalon::class)->find($id);

        if (!$salon) {
            throw $this->createNotFoundException('Salon de coiffure non trouvé');
        }

        // Supprime l'entité de la base de données
        $em->remove($salon);
        $em->flush();

        // Ajoute un message flash de succès
        $this->addFlash('success', 'Salon de coiffure supprimé définitivement.');

        // Redirige vers la route 'app_hair_salon'
        return $this->redirectToRoute('app_hair_salon');
    }
}
