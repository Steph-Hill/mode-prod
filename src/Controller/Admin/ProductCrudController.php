<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController 
{
    // Chemin de base pour le téléchargement des images des produits
    public const PRODUCTS_PATH_BASE = 'upload/images/products';
    public const PRODUCTS_UPLOAD_DIR = 'public/upload/images/products';
    public const ACTION_DUPLICATE = 'duplicate';

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    // Configuration des actions pour le CRUD
    public function configureActions(Actions $actions): Actions
    {
        // Ajout d'un bouton "Dupliquer" pour dupliquer un produit
        $duplicate = Action::new(self::ACTION_DUPLICATE)
            ->linkToCrudAction('duplicateProduct')
            ->setCssClass('btn btn-info');
        return $actions
            ->add(Crud::PAGE_EDIT, $duplicate);
    }

    // Action pour dupliquer un produit
    public function duplicateProduct(
        AdminContext $context,
        EntityManagerInterface $entityManager,
        AdminUrlGenerator $adminUrlGenerator
    ): Response {
        // Récupération de l'instance du produit à dupliquer
        /** @var Product $product */
        $product = $context->getEntity()->getInstance();

        // Clonage du produit
        $duplicateProduct = clone $product;

        // Appel de la méthode persistEntity de l'AbstractCrudController pour persister l'entité clonée
        parent::persistEntity($entityManager, $duplicateProduct);

        // Génération de l'URL pour afficher la page de détail du produit dupliqué
        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($duplicateProduct->getId())
            ->generateUrl();

        // Redirection vers la page de détail du produit dupliqué
        return $this->redirect($url);
    }

    // Configuration des champs du formulaire de création/édition du produit
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            AssociationField::new('category', 'Catégorie')->setFormTypeOptions([
                'class' => Category::class,
                'choice_label' => 'name',
            ]),
            TextEditorField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
            NumberField::new('quantity')->setNumDecimals(0),
            ImageField::new('image')->setBasePath(self::PRODUCTS_PATH_BASE)->setUploadDir(self::PRODUCTS_UPLOAD_DIR)->setSortable(false),
            DateTimeField::new('updatedAt')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }

    // Méthode pour ajouter la date de création (createdAt) lors de la persistance d'un produit
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Product) {
            return;
        }

        $entityInstance->setCreatedAt(new \DateTimeImmutable);
        parent::persistEntity($entityManager, $entityInstance);
    }

    // Méthode pour ajouter la date de mise à jour (updatedAt) lors de la mise à jour d'un produit
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Product) {
            return;
        }

        $entityInstance->setUpdatedAt(new \DateTimeImmutable);
        parent::updateEntity($entityManager, $entityInstance);
    }
}
