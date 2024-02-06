<?php

namespace App\Form;

use App\Entity\HairSalon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class HairSalonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajoute les champs du formulaire avec leurs contraintes de validation
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de votre salon', // Libellé du champ nom du salon
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Type de valeur attendue (chaîne de caractères)
                    new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.'])
                ],
                'attr' => [
                    'class' => 'form-control mb-6' // Classe Bootstrap pour le champ nom du salon
                ]
            ])
            ->add('postalAdress', TextType::class, [
                'label' => 'Adresse postale', // Libellé du champ adresse postale
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Contrainte de longueur minimale et maximale
                    new Length([
                        'min' => 3,
                        'max' => 250,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le message ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control mb-6' // Classe Bootstrap pour le champ adresse postale
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone', // Libellé du champ téléphone
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Type de valeur attendue (chaîne de caractères)
                    new Type(['type' => 'string', 'message' => 'Entrez votre numéro de téléphone'])
                ],
                'attr' => [
                    'class' => 'form-control mb-6' // Classe Bootstrap pour le champ téléphone
                ]
            ])
            ->add('employe', IntegerType::class, [
                'label' => "Nombre d'employés", // Libellé du champ nombre d'employés
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                ],
                'attr' => [
                    'class' => 'form-control mb-6 ' // Classe Bootstrap pour le champ nombre d'employés
                ]
            ])
            ->add('chair', IntegerType::class, [
                'label' => "Nombre de places disponibles", // Libellé du champ nombre de places disponibles
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Type de valeur attendue (entier)
                    new Type(['type' => 'integer', 'message' => 'Entrez le nombre de places disponibles'])
                ],
                'attr' => [
                    'class' => 'form-control mb-6' // Classe Bootstrap pour le champ nombre de places disponibles
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer', // Libellé du bouton d'envoi
                'attr' => [
                    'class' => 'btn btn-secondary btn-block mt-4' // Classe Bootstrap pour le bouton d'envoi
                ]
            ]);

        // Ajoute un bouton de modification si un salon est spécifié et s'il s'agit d'une instance de HairSalon
        if (isset($options['salon']) && $options['salon'] instanceof HairSalon) {
            $builder->add('modifier', ButtonType::class, [
                'label' => 'Modifier', // Libellé du bouton de modification
                'attr' => [
                    'class' => 'btn btn-secondary', // Classe Bootstrap pour le bouton de modification
                    'data-toggle' => 'modal', // Si vous souhaitez utiliser un modal pour la modification
                    'data-target' => '#modifierSalonModal', // ID du modal de modification
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration des options du formulaire
        $resolver->setDefaults([
            'data_class' => HairSalon::class,
        ]);
    }
}
