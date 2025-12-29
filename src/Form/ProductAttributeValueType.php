<?php

namespace App\Form;

use App\Entity\ProductAttributeValue;
use App\Entity\AttributeValue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attributeValue', EntityType::class, [
                'class' => AttributeValue::class,
                'choice_label' => 'value',
                'placeholder' => 'Select Attribute Value',
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'INR',
                'required' => false,
            ])
            ->add('stock', IntegerType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductAttributeValue::class,
        ]);
    }
}
