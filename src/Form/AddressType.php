<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
            ])
            ->add('firstname', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
            ])
            ->add('lastname', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
            ])
            ->add('company', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
            ])
            ->add('address', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
            ])
            ->add('postalcode', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
            ])
            ->add('country', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
            ])
            ->add('phone', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
            ])
            ->add('city', null, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour les champs de texte
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary btn-block mt-4', // Classe Bootstrap pour le bouton
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}

