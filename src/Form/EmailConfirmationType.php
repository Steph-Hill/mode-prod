<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmailConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Ajoute les champs du formulaire avec leurs contraintes de validation
        $builder
            ->add('confirmEmail', SubmitType::class, [
                'label' => 'Confirmer la modification de l\'e-mail',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email', // Libellé du champ email
                'constraints' => [
                    // Validation de format d'email
                    new Email(),
                    // Champ requis
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    // Type de valeur attendue (chaîne de caractères)
                    new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.'])
                ],
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap pour le champ email
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configuration des options du formulaire
        $resolver->setDefaults([]);
    }
}
