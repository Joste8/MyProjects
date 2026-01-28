<?php

namespace App\Form;

use App\Entity\ProductAttributeValue;
use App\Entity\ProductAttribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductAttributeValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attribute', EntityType::class, [
                'class' => ProductAttribute::class,
                'choice_label' => 'name',
                'placeholder' => 'Select Attribute',
                'label' => 'Attribute Name (e.g., Color)'
            ])
            
            ->add('value', TextType::class, [
                'label' => 'Value (e.g., Red)'
            ])
            ->add('price', NumberType::class, [
                'required' => false,
                'label' => 'Extra Price (Optional)'
            ])
            ->add('stock', IntegerType::class, [
                'required' => false,
                'label' => 'Stock Count'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductAttributeValue::class,
        ]);
    }
}