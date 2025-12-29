<?php

namespace App\Form;

use App\Entity\ProductVariant;
use App\Entity\AttributeValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price')
            ->add('stock')

            // 🔑 THIS IS THE MISSING PART
            ->add('attributeValues', EntityType::class, [
                'class' => AttributeValue::class,
                'choice_label' => function (AttributeValue $av) {
                    return $av->getAttribute()->getName() . ' : ' . $av->getValue();
                },
                'multiple' => true,
                'expanded' => false, // dropdown
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductVariant::class,
        ]);
    }
}
