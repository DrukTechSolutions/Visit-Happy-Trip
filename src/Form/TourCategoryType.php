<?php

namespace App\Form;

use App\Entity\TourCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TourCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parent_category', EntityType::class, [
                'mapped' => false,
                'required' => false,
                'class' => TourCategory::class,
                'choice_label' => 'category',
                'placeholder' => 'No parent',
                'query_builder' =>  function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                            ->where('c.sub_category IS NULL')
                            ;  
                }
            ])
            ->add('category', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TourCategory::class,
        ]);
    }
}
