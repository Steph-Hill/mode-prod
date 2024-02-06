<?php

namespace App\Form;

use App\Entity\Transporter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransporterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout des champs au formulaire
        $builder
            ->add('title')   // Champ pour le titre du transporteur
            ->add('content') // Champ pour le contenu du transporteur
            ->add('price');  // Champ pour le prix du transporteur
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration des options du formulaire
        $resolver->setDefaults([
            'data_class' => Transporter::class, // Classe de l'entité utilisée pour les données du formulaire
        ]);
    }
}
