<?php

namespace App\Form;

use App\Entity\HotelsInBhutan;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelsInBhutanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hotel_name')
            ->add('ratings', ChoiceType::class, [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5'
                ]
            ])
            ->add('room_type')
            ->add('no_of_rooms')
            ->add('room_details', TinymceType::class)
            ->add('ammenities', TinymceType::class)
            ->add('phone_no')
            ->add('email')
            ->add('website')
            ->add('address', TextareaType::class)
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'mapped' => false,
                'allow_add' => true,
                'prototype' => true,
                'label' => false,
                'by_reference' => false,
                'prototype_name' => 'hotels_image',
                'entry_options' => [
                    'attr' => [
                        'image' => $options['data']->getImages()
                    ],
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HotelsInBhutan::class,
        ]);
    }
}
