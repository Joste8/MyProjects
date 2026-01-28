<?php

namespace App\Controller\Admin;

use App\Entity\StockLog;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class StockLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StockLog::class;
    }


public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(),
        TextField::new('action', 'Action'),
        TextField::new('description', 'Description'),

        
        DateTimeField::new('createdAt', 'Created At')
            ->setFormTypeOptions([
                'data' => new \DateTimeImmutable(), 
            ])
            ->hideOnForm(), 
    ];
}
}
