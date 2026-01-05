<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield TextEditorField::new('description')->hideOnIndex();
        yield MoneyField::new('price')->setCurrency('INR');

        yield CollectionField::new('variants')
            ->onlyOnIndex()
            ->setTemplatePath('admin/fields/variant_badges.html.twig')
            ->setLabel('Attributes / Variants');

        yield CollectionField::new('variants')
            ->hideOnIndex()
            ->useEntryCrudForm(ProductVariantCrudController::class)
            ->setLabel('Product Variants (Size, Color, Stock)');
    }
}