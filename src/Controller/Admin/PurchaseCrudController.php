<?php

namespace App\Controller\Admin;

use App\Entity\Purchase;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class PurchaseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Purchase::class;
    }

    public function configureFields(string $pageName): iterable
{
    $quantityField = IntegerField::new('quantity', 'Qty');
    if ($pageName === 'index') {
        $quantityField->formatValue(function ($value) {
            return sprintf('<span class="badge badge-info shadow-sm" style="padding: 5px 10px;">%s</span>', $value);
        });
    }

    return [
        TextField::new('itemName', 'Product Name')
            ->setCssClass('fw-bold text-primary'),

        AssociationField::new('customer', 'Customer'),

        MoneyField::new('price', 'Unit Price')
            ->setCurrency('INR')
            ->setStoredAsCents(false),

        $quantityField,
        MoneyField::new('totalPrice', 'Total Amount')
            ->setCurrency('INR')
            ->setStoredAsCents(false)
            ->setCssClass('text-success fw-bold'),
    ];
}
}