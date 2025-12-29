<?php

namespace App\Controller\Admin;

use App\Entity\ProductAttributeValue;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ProductAttributeValueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductAttributeValue::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('product')->setRequired(true),
            AssociationField::new('attributeValue')->setRequired(true),
            MoneyField::new('price')->setCurrency('INR'),
            IntegerField::new('stock'),
        ];
    }
}
