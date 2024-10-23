<?php

namespace App\Form;

use App\Entity\TourPackage;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TourPackageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tour_category', ChoiceType::class, [
                'choices' => [
                    'Cultural Tour' => 'cultural-tour',
                    'Festival Tour' => 'festival-tour',
                    'Adventure Tour' => 'adventure-tour',
                    'Trekking Tour' => 'trekking-tour',
                ]
            ])
            ->add('price')
            ->add('tour_overview', TinymceType::class)
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'mapped' => false,
                'allow_add' => true,
                'prototype' => true,
                'label' => false,
                'by_reference' => false,
                'prototype_name' => 'tour_image',
                'entry_options' => [
                    'attr' => [
                        'image' => $options['data']->getImages()
                    ],
                ],
            ])
            ->add('itinerary', CollectionType::class, [
                'entry_type' => ItineraryType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'label' => false,
                'by_reference' => false,
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
