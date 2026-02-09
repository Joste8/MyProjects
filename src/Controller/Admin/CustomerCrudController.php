<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Form\PurchaseType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class CustomerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return Customer::class; }

public function configureFields(string $pageName): iterable
{
    yield TextField::new('name', 'Customer Name');
    yield EmailField::new('email', 'Email Address');
    
    yield TextField::new('phone', 'Phone Number')
        ->formatValue(function ($value) { return $value ?? '---'; });

    yield TextField::new('address', 'Address')
        ->formatValue(function ($value) { return $value ?? '---'; });

    yield IntegerField::new('totalItemsCount', 'Total Products')->onlyOnIndex();
    yield MoneyField::new('grandTotal', 'Grand Total')
        ->setCurrency('INR')
        ->setStoredAsCents(false)
        ->onlyOnIndex();

    yield CollectionField::new('purchases', 'Add Products')
        ->setEntryType(PurchaseType::class)
        ->setFormTypeOptions(['by_reference' => false])
        ->onlyOnForms();
}
}