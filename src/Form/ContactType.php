<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Email;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout des champs du formulaire avec leurs contraintes de validation
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre nom/prénom',
                'constraints' => [
                    // Longueur minimale et maximale du nom/prénom
                    new Length([
                        'min' => 5,
                        'max' => 180
                    ]),
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Type de valeur attendue (chaîne de caractères)
                    new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.'])
                ],
                'attr' => [
                    'class' => 'form-control mb-6'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Type de valeur attendue (chaîne de caractères)
                    new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.']),
                    // Validation de format d'email
                    new Email()
                ],
                'attr' => [
                    'class' => 'form-control mb-6'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Type de valeur attendue (chaîne de caractères)
                    new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.'])
                ],
                'attr' => [
                    'class' => 'form-control mb-6'
                ]
            ])
            ->add('postalAddress', TextType::class, [
                'label' => 'Adresse postale',
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Longueur minimale et maximale de l'adresse postale
                    new Length([
                        'min' => 10,
                        'max' => 250,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le message ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control mb-6'
                ]
            ])
            ->add('codePostal', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Type de valeur attendue (chaîne de caractères)
                    new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.'])
                ],
                'attr' => [
                    'class' => 'form-control mb-6'
                ]
            ])
            ->add('objet', TextType::class, [
                'label' => 'Objet',
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Longueur minimale et maximale de l'objet
                    new Length([
                        'min' => 10,
                        'max' => 150,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le message ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control mb-6'
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-6',
                    'rows' => 5
                ],
                'constraints' => [
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Longueur minimale et maximale du message
                    new Length([
                        'min' => 10,
                        'max' => 150,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le message ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary btn-block mt-4 mb-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration des options du formulaire
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
