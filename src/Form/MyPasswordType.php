<?php

namespace App\Form;

use App\Entity\Professional;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class MyPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajoute le champ de mot de passe avec ses attributs et ses contraintes de validation
        $builder->add('password', PasswordType::class, [
            'label' => 'Nouveau mot de passe', // Libellé du champ de mot de passe
            'attr' => [
                'autocomplete' => 'new-password', // Indique au navigateur de ne pas autofill le champ
                'class' => 'form-control mb-6' // Classe Bootstrap pour le champ de mot de passe
            ],
            'constraints' => [
                // Champ requis
                new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                // Longueur minimale du mot de passe
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.'
                ])
            ],
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
