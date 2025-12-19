<?php

namespace App\Form;

use App\Entity\VariantAttribute;
use App\Entity\Attribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariantAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attribute', EntityType::class, [
                'class' => Attribute::class,
                'choice_label' => 'name', // __toString() ഉള്ളതിനാൽ optional
                'placeholder' => 'Select Attribute',
            ])
            ->add('value');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VariantAttribute::class,
        ]);
    }
}
