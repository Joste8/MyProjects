<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductVariantType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


public function configureFields(string $pageName): iterable
{
    yield TextField::new('name', 'Product Name')
        ->setHtmlAttributes(['style' => 'font-weight: 800; color: #a5d6a7; font-size: 1.1em;']); // ലൈറ്റ് ഗ്രീൻ ഷേഡ്

    yield NumberField::new('price', 'Price')
        ->formatValue(function ($value) {
            return sprintf('<span style="color: #2ecc71; font-weight: bold;">₹%s</span>', number_format($value, 2));
        });

    yield IntegerField::new('stock', 'Total Stock')
        ->formatValue(function ($value) {
            if ($value < 5) {
                return sprintf('<span style="background-color: #ffdce0; color: #af233a; padding: 4px 8px; border-radius: 4px; font-weight: bold;">⚠️ %d (Low)</span>', $value);
            }
            return sprintf('<strong>%d</strong> Units', $value);
        });

    yield TextField::new('variantDetails', 'Variant Info')
        ->onlyOnIndex()
        ->formatValue(function ($value) {
            if (!$value || $value === 'No Variants') return '---';
            $items = explode(', ', $value);
            $html = '';
            foreach ($items as $item) {
                $html .= sprintf('<span style="background-color: #ffffff; color: #2c3e50; padding: 3px 12px; margin: 2px; border-radius: 20px; font-size: 11px; display: inline-block; border: 1px solid #bdc3c7; font-weight: 600; box-shadow: 1px 1px 2px rgba(0,0,0,0.1);">%s</span>', $item);
            }
            return $html;
        });

    yield AssociationField::new('subCategory', 'Category');

    yield ChoiceField::new('status', 'Status')
        ->setChoices([
            'Active' => 'Active',
            'Inactive' => 'Inactive',
            'Out of Stock' => 'Out of Stock'
        ])
        ->renderAsBadges([
            'Active' => 'success',
            'Inactive' => 'danger',
            'Out of Stock' => 'warning'
        ]);

    yield CollectionField::new('variants')
        ->setEntryType(ProductVariantType::class)
        ->setFormTypeOptions(['by_reference' => false])
        ->hideOnIndex();
}

}