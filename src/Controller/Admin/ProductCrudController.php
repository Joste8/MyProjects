<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductVariantType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud; // ✅ IMPORTANT

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),

            IntegerField::new('totalStock', 'Total Stock')
                ->onlyOnIndex(),

            TextField::new('variantAttributes', 'Attributes')
                ->onlyOnIndex(),

            CollectionField::new('variants')
                ->setEntryType(ProductVariantType::class)
                ->allowAdd()
                ->allowDelete()
                ->renderExpanded()
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
    }
}
