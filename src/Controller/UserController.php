<?php

namespace App\Controller;

use App\Entity\Professional;
use App\Form\MyPasswordType;
use App\Form\ProfessionalType;
use App\Form\EmailConfirmationType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    #[Route('/utilisateur', name: 'user_profil')]
    public function index(): Response
    {
        /* Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion */
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $professional = $this->getUser();

        return $this->render('user/index.html.twig', [
            'professional' => $professional,
        ]);
    }
    #[Route('/utilisateur/editionPersonel/{id}', name: 'user_edit', methods: ['GET', 'POST'])]
    public function editPersonal(
        Professional $professional,
        Request $request,
        EntityManagerInterface $em,
        $id
    ): Response {

        /*si utilisateur non connecté redirige a la page conneion */
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        /****************************************************************
         * si un autre utilisateur tante de modifier le profil d'un autre 
         *    on le redirige vers la page d'accueil
         *****************************************************************/

        if ($this->getUser() !== $professional) {

            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(ProfessionalType::class, $professional);

        //Gestion de la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $professional = $form->getData();

            $em->persist($professional);

            $em->flush();

            $this->addFlash('success', 'Salon de coiffure mis à jour avec succès.');

            return $this->redirectToRoute('index'); // Redirigez où vous le souhaitez
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur/editEmail/{id}', name: 'user_email', methods: ['GET', 'POST'])]
    public function editEmail(Request $request,
                            Professional $professional,
                                EntityManagerInterface $entityManager)
    {
        // Créez un formulaire pour la confirmation de l'e-mail
        $emailConfirmationForm = $this->createForm(EmailConfirmationType::class);

        // Gérez la soumission du formulaire
        $emailConfirmationForm->handleRequest($request);

        if ($emailConfirmationForm->isSubmitted() && $emailConfirmationForm->isValid()) {
            // Le formulaire de confirmation d'e-mail a été soumis
            $newEmail = $emailConfirmationForm->get('email')->getData();

            // Mettez à jour l'e-mail de l'utilisateur
            $professional->setEmail($newEmail);

            // Effectuez d'autres actions nécessaires, par exemple, envoi d'une notification

            // Enregistrez les modifications dans la base de données
            $entityManager->persist($professional);
            $entityManager->flush();

            // Redirigez où vous le souhaitez après la confirmation de l'e-mail
            // Par exemple, redirigez vers la page de profil de l'utilisateur
            return $this->redirectToRoute('user_profil');
        }

        // ...

        return $this->render('user/editEmail.html.twig', [
            'emailConfirmationForm' => $emailConfirmationForm->createView(),
        ]);
    }

    #[Route('/utilisateur/editPassword/{id}', name: 'user_pass', methods: ['GET', 'POST'])]
    public function editPass(Request $request,
                            Professional $professional,
                            EntityManagerInterface $entityManager,
                            UserPasswordHasherInterface $passwordHasher)
    {


        // Créez un formulaire pour la confirmation de l'e-mail
        $resetForm = $this->createForm(MyPasswordType::class);

        // Gérez la soumission du formulaire
        $resetForm->handleRequest($request);
        
        // Le formulaire de confirmation de Mdp a été soumis
        if ($resetForm->isSubmitted() && $resetForm->isValid()) {
            
            // Encodez le nouveau mot de passe
            $encodedPassword = $passwordHasher->hashPassword(
                $professional,
                $resetForm->get('password')->getData()
            );

            // Mettez à jour le mdp de l'utilisateur
            $professional->setPassword($encodedPassword);

            // Effectuez d'autres actions nécessaires, par exemple, envoi d'une notification

            // Enregistrez les modifications dans la base de données
            $entityManager->persist($professional);
            $entityManager->flush();

            // Par exemple, redirigez vers la page de profil de l'utilisateur
            return $this->redirectToRoute('user_profil');
        }

        // ...

        return $this->render('user/passEdit.html.twig', [
            'resetForm' => $resetForm->createView(),
        ]);
    }
}
