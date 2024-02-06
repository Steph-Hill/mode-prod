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
        // Ajoute le champ 'email' pour l'email de l'utilisateur
        $builder->add('email', EmailType::class, [
            'label' => 'Email', // Étiquette du champ
            'constraints' => [ // Contraintes de validation pour le champ
                new Email(), // L'email doit être valide
                new NotBlank(['message' => 'Ce champ ne peut pas être vide.']), // Le champ ne peut pas être vide
                new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.']) // Le champ doit être une chaîne de caractères
            ],
            'attr' => [ // Attributs HTML supplémentaires pour le champ
                'class' => 'form-control', // Classe Bootstrap pour le champ
            ],
        ])
        ->add('name', null, [
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
        ->add('hairSalon', ChoiceType::class, [
            'label' => 'Possédez-vous un ou plusieurs Salon de coiffure ?', // Étiquette du champ
            'choices' => [ // Choix possibles pour le champ
                'Non je ne possède pas' => false, // Valeur et libellé du premier choix
                "Oui j'en possède" => true, // Valeur et libellé du deuxième choix
            ],
            'attr' => [ // Attributs HTML supplémentaires pour le champ
                'class' => 'flex-d', // Classe Bootstrap pour le champ
            ],
            'expanded' => false, // Affiche les choix sous forme de boutons radio
            'multiple' => false, // Permet à l'utilisateur de choisir une seule réponse
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false, // Ne pas mapper ce champ à une propriété de l'entité
            'label' => 'I agree to the terms', // Étiquette du champ
            'attr' => [ // Attributs HTML supplémentaires pour le champ
                'class' => 'form-check-input', // Classe Bootstrap pour le champ
            ],
            'constraints' => [ // Contraintes de validation pour le champ
                new IsTrue([ // Le champ doit être égal à true
                    'message' => 'You should agree to our terms.', // Message en cas de validation échouée
                ]),
            ],
        ])
        ->add('plainPassword', PasswordType::class, [
            'mapped' => false, // Ne pas mapper ce champ à une propriété de l'entité
            'label' => 'Password', // Étiquette du champ
            'attr' => [ // Attributs HTML supplémentaires pour le champ
                'class' => 'form-control', // Classe Bootstrap pour le champ
                'autocomplete' => 'new-password', // Indique au navigateur de ne pas autofill le champ
            ],
            'constraints' => [ // Contraintes de validation pour le champ
                new NotBlank(['message' => 'Ce champ ne peut pas être vide.']), // Le champ ne peut pas être vide
                new Length([ // Le champ doit respecter une longueur spécifique
                    'min' => 8, // Longueur minimale du champ
                    'max' => 20, // Longueur maximale du champ
                    'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.', // Message en cas de longueur minimale non respectée
                    'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.' // Message en cas de longueur maximale dépassée
                ]),
                new Regex([ // Le champ doit respecter une expression régulière spécifique
                    'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&-])[A-Za-z\d@$!%*?&]+$/',
                    'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.' // Message en cas de validation échouée
                ]),
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

    public function onPreSubmit(FormEvent $event): void
    {
        // Méthode appelée avant la soumission du formulaire
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
