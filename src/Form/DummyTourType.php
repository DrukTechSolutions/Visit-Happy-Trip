<?php

namespace App\Form;

use App\Entity\TourCategory;
use App\Entity\TourPackage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DummyTourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tour_category')
            ->add('price')
            ->add('tour_overview')
            ->add('tour_title')
            ->add('tour_title_slug')
            ->add('tourCategories', EntityType::class, [
                'class' => TourCategory::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TourPackage::class,
        ]);
    }
}
