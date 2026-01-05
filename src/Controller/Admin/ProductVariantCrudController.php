<?php

namespace App\Controller\Admin;

use App\Entity\ProductVariant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class ProductVariantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductVariant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('size')
                ->setLabel('Size'),
            TextField::new('color')
                ->setLabel('Color'),
            NumberField::new('stock')
                ->setLabel('Stock Quantity'),
            MoneyField::new('price')
                ->setCurrency('INR')
                ->setLabel('Price for this variant'),
        ];
    }
} 