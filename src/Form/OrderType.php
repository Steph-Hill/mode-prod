<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Transporter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupère l'option 'user' passée lors de la configuration du formulaire
        $user = $options['user'];

        $builder
            ->add('addresses',EntityType::class, [
                'class' => Address::class,
                'label' => false,
                'required' => true,
                'multiple' => false,
                'choices' => $user->getAddresses(),
                'expanded' => true,
                'attr' => [
                    'class' => 'row form-check-inline' // Replace 'form-control' with the desired Bootstrap class
                ]
            ])
            ->add('transporter',EntityType::class, [
                'class' => Transporter::class,
                'label' => false,
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'row form-check-inline' // Replace 'form-control' with the desired Bootstrap class
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration des options du formulaire
        $resolver->setDefaults([
            'user' => []
        ]);
    }
}
