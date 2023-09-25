<?php

namespace App\Form;

use App\Entity\Professional;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ProfessionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, [
            'label' => 'Nom/Prénom',
            'constraints' => [
                new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.']),
                new Length([
                    'min' => 10,
                    'max' => 100,
                    'minMessage' => 'Le Nom/Prénom doit contenir au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le Nom/Prénom ne peut pas dépasser {{ limit }} caractères.'
                ])
            ],
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('postalAdress', null, [
            'label' => 'Addresse Postal',
            'constraints' => [
                new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.']),
                new Length([
                    'min' => 10,
                    'max' => 250,
                    'minMessage' => `L'addresse Postal doit contenir au moins {{ limit }} caractères.`,
                    'maxMessage' => `L'addresse Postal ne peut pas dépasser {{ limit }} caractères.`
                ])
            ],
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('siret', null, [
            'label' => 'Siret',
            'constraints' => [
                new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.'])
                
            ],
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Envoyer',
            'attr' => [
                'class' => 'btn btn-primary btn-block mt-4'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
        ]);
    }


}
