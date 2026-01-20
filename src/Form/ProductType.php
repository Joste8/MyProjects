<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\SubCategory; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('price', null, [
                'attr' => ['class' => 'form-control']
            ])
           
            ->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a sub-category',
                'group_by' => function(SubCategory $sub) {
                    return $sub->getCategory()->getName(); 
                },
                'attr' => ['class' => 'form-control select2']
            ])
            ->add('attributeValues', CollectionType::class, [
                'entry_type' => ProductAttributeValueType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}