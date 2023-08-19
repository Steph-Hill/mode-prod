<?php

namespace App\Controller\Admin;

use App\Entity\Transporter;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TransporterCrudController extends AbstractCrudController
{
    // Méthode pour obtenir le nom complet de l'entité
    public static function getEntityFqcn(): string
    {
        return Transporter::class;
    }

    // Méthode pour configurer les champs du formulaire pour chaque page
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // Champ ID, masqué dans le formulaire
            TextField::new('title'), // Champ texte pour le titre
            TextField::new('content'), // Champ texte pour le contenu
            MoneyField::new('price')->setCurrency('EUR'), // Champ d'argent pour le prix, avec la devise EUR
        ];
    }
}
