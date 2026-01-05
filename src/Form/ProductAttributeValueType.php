<?php

namespace App\Form;

use App\Entity\ProductAttributeValue;
use App\Entity\ProductAttribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ])
            ->add('attributeValue', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductAttributeValue::class,
        ]);
    }
}
