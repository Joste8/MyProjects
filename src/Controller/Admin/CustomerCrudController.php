<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField; 
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class CustomerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Customer::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    

public function configureFields(string $pageName): iterable
{
    yield TextField::new('name', 'Customer Name');
    yield EmailField::new('email', 'Email Address');
    yield TelephoneField::new('phone', 'Phone Number');
    yield TextareaField::new('address', 'Address');

    if ($pageName === Crud::PAGE_DETAIL) {
        
        yield FormField::addPanel('Insights & History');

        
        yield IdField::new('id', 'Smart Recommendations')
            ->onlyOnDetail()
            ->setTemplatePath('admin/customer/recommendations.html.twig');

        
        yield CollectionField::new('purchases', 'Purchase History')
            ->onlyOnDetail()
            ->setTemplatePath('admin/purchase/history.html.twig');

        yield MoneyField::new('grandTotal', 'Overall Spent')
            ->setCurrency('INR')
            ->setStoredAsCents(false)
            ->onlyOnDetail();
    }
}
}