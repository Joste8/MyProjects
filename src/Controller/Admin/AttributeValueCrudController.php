<?php

namespace App\Controller\Admin;

use App\Entity\AttributeValue;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AttributeValueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AttributeValue::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('value'),
            AssociationField::new('attribute'),
        ];
    }
}
