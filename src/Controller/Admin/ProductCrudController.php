<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Inventory Management')
            ->renderContentMaximized()
            ->setDefaultSort(['id' => 'DESC'])
            ->overrideTemplate('crud/index', 'admin/product/index.html.twig');
    }

    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore 
    {
        $em = $this->container->get('doctrine')->getManager();
        $responseParameters->set(
            'products',
            $em->getRepository(Product::class)->findAll()
        );

        return parent::configureResponseParameters($responseParameters);
    }

  public function configureFields(string $pageName): iterable
{
    return [
        
        IdField::new('id', 'ID')
            ->hideOnForm()
            ->setColumns('col-md-1'),

        TextField::new('name', 'Product Name')
            ->setColumns('col-md-3')
            ->setCssClass('fw-bold text-primary'),

        MoneyField::new('price', 'Price')
            ->setCurrency('INR')
            ->setStoredAsCents(false)
            ->setTextAlign('center')
            ->setColumns('col-md-2'),

        IntegerField::new('stock', 'Initial Stock')
            ->setColumns('col-md-2')
            ->formatValue(function ($value) {
                if ($value <= 0) return sprintf('<span class="badge rounded-pill bg-danger shadow-sm">Out of Stock (%d)</span>', $value);
                if ($value < 10) return sprintf('<span class="badge rounded-pill bg-warning text-dark shadow-sm">Low Stock (%d)</span>', $value);
                return sprintf('<span class="badge rounded-pill bg-success shadow-sm">In Stock (%d)</span>', $value);
            }),

        
        CollectionField::new('attributeValues', 'Product Variants')
            ->useEntryCrudForm(ProductAttributeValueCrudController::class) 
            ->setColumns('col-md-4')
            ->formatValue(function ($value, $entity) {
                $html = '';
                foreach ($entity->getAttributeValues() as $attr) {
                    $html .= sprintf('<span class="variant-badge">%s</span>', $attr->getValue());
                }
                return $html ?: '<small class="text-muted">No variants</small>';
            }),
    ];
}
}
