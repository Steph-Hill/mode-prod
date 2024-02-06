<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert; // Importation des contraintes de validation Symfony

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ 'title' avec la contrainte de non-vide (NotBlank)
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Contrainte de non-vide
                ],
            ])
            // Champ 'firstname' avec la contrainte de non-vide (NotBlank)
            ->add('firstname', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Contrainte de non-vide
                ],
            ])
            // Champ 'lastname' avec la contrainte de non-vide (NotBlank)
            ->add('lastname', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Contrainte de non-vide
                ],
            ])
            // Champ 'company' avec la contrainte de non-vide (NotBlank)
            ->add('company', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Contrainte de non-vide
                ],
            ])
            // Champ 'address' avec la contrainte de non-vide (NotBlank)
            ->add('address', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Contrainte de non-vide
                ],
            ])
            // Champ 'postalcode' avec les contraintes de non-vide (NotBlank) et de type numérique (Type)
            ->add('postalcode', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Contrainte de non-vide
                    new Assert\Type(['type' => 'numeric']), // Contrainte de type numérique
                ],
            ])
            // Champ 'country' avec la contrainte de non-vide (NotBlank)
            ->add('country', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Contrainte de non-vide
                ],
            ])
            // Champ 'phone' avec la contrainte de non-vide (NotBlank)
            ->add('phone', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Contrainte de non-vide
                ],
            ])
            // Champ 'city' avec la contrainte de non-vide (NotBlank)
            ->add('city', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Contrainte de non-vide
                ],
            ])
            // Champ 'submit' (bouton Envoyer) avec la contrainte de non-vide (NotBlank)
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary btn-block mt-4', // Classe Bootstrap pour le bouton
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Contrainte de non-vide
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class, // Classe de données par défaut pour ce formulaire
        ]);
    }
}
