<?php

namespace App\Form;

use App\Entity\Professional;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new Email(),
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.'])
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
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
            ->add('hairSalon', ChoiceType::class, [
                'label' => 'Possédez-vous un ou plusieurs Salon de coiffure ?',
                'choices' => [
                    'Non je ne possède pas' => false,
                    "Oui j'en possède" => true,
                ],
                'attr' => [
                    'class' => 'flex-d', // Notez que 'attr' est un tableau
                ],
                'expanded' => false, // Affiche les choix sous forme de boutons radio
                'multiple' => false, // Permet à l'utilisateur de choisir une seule réponse
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'I agree to the terms',
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Password',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'new-password',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length([
                        'min' => 8,
                        'max' => 20,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&-])[A-Za-z\d@$!%*?&]+$/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.'
                    ]),
                ],
                
            ]);
            
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professional::class, // Classe de l'entité utilisée pour les données du formulaire
        ]);
    }

    public function onPreSubmit(FormEvent $event): void
    {
        $user = $event->getData();
    
        // Récupérer la valeur de hairSalon
        $hairSalon = $user['hairSalon'];
    
        // Mettre à jour le rôle en fonction de la valeur de hairSalon
        if ($hairSalon === true) {
            $user['roles'] = ['ROLE_PROFESSIONAL_SALON'];
        } else {
            $user['roles'] = ['ROLE_PROFESSIONAL'];
        }
    
        // Réaffecter les données mises à jour au formulaire
        $event->setData($user);
    }
}
