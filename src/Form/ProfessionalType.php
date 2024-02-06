<?php

namespace App\Form;

use App\Entity\Professional;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfessionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajoute le champ 'name' pour le nom/prénom du professionnel
        $builder->add('name', null, [
            'label' => 'Nom/Prénom', // Étiquette du champ
            'constraints' => [ // Contraintes de validation pour le champ
                new NotBlank(['message' => 'Ce champ ne peut pas être vide.']), // Le champ ne peut pas être vide
                new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.']), // Le champ doit être une chaîne de caractères
                new Length([ // Le champ doit respecter une longueur spécifique
                    'min' => 10, // Longueur minimale du champ
                    'max' => 100, // Longueur maximale du champ
                    'minMessage' => 'Le Nom/Prénom doit contenir au moins {{ limit }} caractères.', // Message en cas de longueur minimale non respectée
                    'maxMessage' => 'Le Nom/Prénom ne peut pas dépasser {{ limit }} caractères.' // Message en cas de longueur maximale dépassée
                ])
            ],
            'attr' => [ // Attributs HTML supplémentaires pour le champ
                'class' => 'form-control', // Classe Bootstrap pour le champ
            ],
        ])
        ->add('postalAdress', null, [
            'label' => 'Addresse Postal', // Étiquette du champ
            'constraints' => [ // Contraintes de validation pour le champ
                new NotBlank(['message' => 'Ce champ ne peut pas être vide.']), // Le champ ne peut pas être vide
                new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.']), // Le champ doit être une chaîne de caractères
                new Length([ // Le champ doit respecter une longueur spécifique
                    'min' => 10, // Longueur minimale du champ
                    'max' => 250, // Longueur maximale du champ
                    'minMessage' => 'L\'addresse Postal doit contenir au moins {{ limit }} caractères.', // Message en cas de longueur minimale non respectée
                    'maxMessage' => 'L\'addresse Postal ne peut pas dépasser {{ limit }} caractères.' // Message en cas de longueur maximale dépassée
                ])
            ],
            'attr' => [ // Attributs HTML supplémentaires pour le champ
                'class' => 'form-control', // Classe Bootstrap pour le champ
            ],
        ])
        ->add('siret', null, [
            'label' => 'Siret', // Étiquette du champ
            'constraints' => [ // Contraintes de validation pour le champ
                new NotBlank(['message' => 'Ce champ ne peut pas être vide.']), // Le champ ne peut pas être vide
                new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.']) // Le champ doit être une chaîne de caractères
            ],
            'attr' => [ // Attributs HTML supplémentaires pour le champ
                'class' => 'form-control', // Classe Bootstrap pour le champ
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Envoyer', // Étiquette du bouton de soumission
            'attr' => [ // Attributs HTML supplémentaires pour le bouton de soumission
                'class' => 'btn btn-primary btn-block mt-4' // Classe Bootstrap pour le bouton de soumission
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration des options du formulaire
        $resolver->setDefaults([
            'data_class' => Professional::class, // Classe de l'entité utilisée pour les données du formulaire
        ]);
    }
}
