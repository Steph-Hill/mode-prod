<?php

namespace App\Form;

use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      
        
        $builder
            ->add('query', TextType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('Recherche via le code postal'),
                    'class' => 'form-control mb-10 m-3 text-center' // Placeholder du champ de saisie
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Type(['type' => 'string', 'message' => 'Ce champ doit être une chaîne de caractères.']),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Ce champ doit contenir uniquement des chiffres.'
                    ])
                ],
                'empty_data' => '', // Valeur par défaut du champ de saisie
                'required' => false // Champ de saisie requis
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Recherche',
                'attr' => [
                    'class' => ' btn bg-primary m-3 px-3s text-bg-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class, // Classe de l'entité utilisée pour les données du formulaire
            'method' => 'GET', // Méthode de soumission du formulaire
            'csrf_protection' => false // Désactiver la protection CSRF pour ce formulaire
        ]);
    }
}
