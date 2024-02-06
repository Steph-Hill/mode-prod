<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout du champ plainPassword en utilisant RepeatedType pour saisir le mot de passe deux fois
        $builder->add('plainPassword', RepeatedType::class, [
            // Type de champ pour la saisie du mot de passe
            'type' => PasswordType::class,
            'options' => [
                // Options supplémentaires pour le champ
                'attr' => [
                    // Empêche le navigateur de suggérer des mots de passe existants
                    'autocomplete' => 'new-password',
                ],
            ],
            'first_options' => [
                // Options pour le premier champ de saisie du mot de passe
                'constraints' => [
                    // Contraintes de validation pour assurer que le champ n'est pas vide et a une longueur minimale
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        // Longueur minimale du mot de passe
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // Longueur maximale du mot de passe pour des raisons de sécurité
                        'max' => 4096,
                    ]),
                ],
                'label' => 'New password', // Libellé du premier champ
            ],
            'second_options' => [
                // Options pour le deuxième champ de saisie du mot de passe
                'label' => 'Repeat Password', // Libellé du deuxième champ
            ],
            'invalid_message' => 'The password fields must match.', // Message d'erreur si les mots de passe ne correspondent pas
            'mapped' => false, // Champ non associé à une propriété d'entité directement
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Aucune option par défaut configurée
        $resolver->setDefaults([]);
    }
}
