<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\StockLog; 
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

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
      
        $responseParameters->set('products', $em->getRepository(Product::class)->findAll());

      
        $responseParameters->set('history', $em->getRepository(StockLog::class)->findAll());

        return parent::configureResponseParameters($responseParameters);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('name', 'Product Name'),
            AssociationField::new('subCategory', 'Sub Category'),
            MoneyField::new('price', 'Price')->setCurrency('INR')->setStoredAsCents(false),
            IntegerField::new('stock', 'Stock Quantity'),
        ];
    }
}