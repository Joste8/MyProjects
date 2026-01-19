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
// src/Controller/Admin/StockLogCrudController.php

public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(),
        TextField::new('action', 'Action'),
        TextField::new('description', 'Description'),

        // തീയതിയും സമയവും ഓട്ടോമാറ്റിക്കായി സെറ്റ് ചെയ്യുന്നു
        DateTimeField::new('createdAt', 'Created At')
            ->setFormTypeOptions([
                'data' => new \DateTimeImmutable(), // DateTime എന്നതിന് പകരം DateTimeImmutable ഉപയോഗിക്കുക
            ])
            ->hideOnForm(), // ഇത് ഫോമിൽ നിന്ന് ഹൈഡ് ചെയ്യുന്നതാണ് നല്ലത്, കാരണം സിസ്റ്റം ഇത് തനിയെ എടുക്കും
    ];
}
}
