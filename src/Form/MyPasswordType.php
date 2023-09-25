<?php

namespace App\Form;

use App\Entity\Professional;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class MyPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('password', PasswordType::class, [
            'label' => 'Nouveau mot de passe',
            'attr' => [
                'autocomplete' => 'new-password', // Indique au navigateur de ne pas autofill le champ
            ],
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professional::class, // Classe de l'entité utilisée pour les données du formulaire
        ]);
    }
}
