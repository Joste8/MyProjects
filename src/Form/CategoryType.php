<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $category = $options['data'];

        $builder
            ->add('name', TextType::class, [
                'label' => 'Category Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('parent', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => '-- Select Main Category (Optional) --',
                'attr' => ['class' => 'form-control'],
                'query_builder' => function (EntityRepository $er) use ($category) {
                    $qb = $er->createQueryBuilder('c');
                    if ($category && $category->getId()) {
                        $qb->andWhere('c.id != :id')
                           ->setParameter('id', $category->getId());
                    }
                    return $qb->orderBy('c.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}