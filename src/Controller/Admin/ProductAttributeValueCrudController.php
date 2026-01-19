<?php

namespace App\Controller\Admin;

use App\Entity\ProductAttributeValue;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class ProductAttributeValueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductAttributeValue::class;
    }

    public function configureFields(string $pageName): iterable
    {
        
        yield AssociationField::new('attribute', 'Type'); 
        
        
        yield TextField::new('value', 'Value'); 
        
        
        yield IntegerField::new('stock', 'Stock Quantity');
    }
}

