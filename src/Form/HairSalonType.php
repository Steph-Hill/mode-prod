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
        
        $builder
            ->add('name', TextType::class,[
                'label'=>'Nom de votre salon',
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.'])
                ],
                'attr' => [
                    'class' => 'form-control mb-6'
                    
                ]
            ])
            ->add('postalAdress', TextType::class, [
                'label' => 'Adresse postale',
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length([
                        'min' => 3,
                        'max' => 250,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le message ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control mb-6'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Type(['type' => 'string', 'message' => 'Entrez votre numéro de téléphone'])
                ],
                'attr' => [
                    'class' => 'form-control mb-6'
                ]
            ])
            ->add('employe', IntegerType::class, [
                'label' => "Nombre d'employés",
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                ],
                'attr' => [
                    'class' => 'form-control mb-6 '
                ]
            ])
            ->add('chair', IntegerType::class,[
                'label' => "Nombre de places disponibles",
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Type(['type' => 'integer', 'message' => 'Entrez le nombre de places disponibles'])
                ],
                'attr' => [
                    'class' => 'form-control mb-6'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-secondary btn-block mt-4'
                ]
            ]);
            if (isset($options['salon']) && $options['salon'] instanceof HairSalon) {
                $builder->add('modifier', ButtonType::class, [
                    'label' => 'Modifier',
                    'attr' => [
                        'class' => 'btn btn-secondary',
                        'data-toggle' => 'modal', // Si vous souhaitez utiliser un modal pour la modification
                        'data-target' => '#modifierSalonModal', // ID du modal de modification
                    ],
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HairSalon::class,
        ]);
    }
}
