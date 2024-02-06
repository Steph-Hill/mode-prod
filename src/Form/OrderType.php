<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Transporter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupère l'option 'user' passée lors de la configuration du formulaire
        $user = $options['user'];

        // Ajoute le champ 'addresses' qui permet à l'utilisateur de choisir une adresse parmi celles associées à son compte
        $builder->add('addresses', EntityType::class, [
            'class' => Address::class, // Entité utilisée pour les options du champ
            'label' => false, // Désactive l'étiquette du champ
            'required' => true, // Rend le champ obligatoire
            'multiple' => false, // Autorise la sélection d'une seule adresse
            'choices' => $user->getAddresses(), // Options disponibles pour le champ, récupérées depuis l'utilisateur actuel
            'expanded' => true, // Affiche les options sous forme de boutons radio
            'attr' => [
                'class' => 'row form-check-inline' // Classe Bootstrap pour le champ
            ]
        ]);

        // Ajoute le champ 'transporter' qui permet à l'utilisateur de choisir un transporteur pour la livraison
        $builder->add('transporter', EntityType::class, [
            'class' => Transporter::class, // Entité utilisée pour les options du champ
            'label' => false, // Désactive l'étiquette du champ
            'required' => true, // Rend le champ obligatoire
            'multiple' => false, // Autorise la sélection d'un seul transporteur
            'expanded' => true, // Affiche les options sous forme de boutons radio
            'attr' => [
                'class' => 'row form-check-inline' // Classe Bootstrap pour le champ
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration des options du formulaire
        $resolver->setDefaults([
            'user' => [] // Définit une option par défaut pour l'utilisateur, vide par défaut
        ]);
    }
}
