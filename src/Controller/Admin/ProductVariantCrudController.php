<?php

namespace App\Controller\Admin;

use App\Entity\ProductVariant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductVariantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return ProductVariant::class; }

    public function configureFields(string $pageName): iterable
    {
        return [
        
            TextField::new('name', 'Variant Name/Value (e.g., Red, XL)'),
            IntegerField::new('stock', 'Stock Quantity'),
           
            AssociationField::new('product', 'Select Product'),
        ];
    }
}