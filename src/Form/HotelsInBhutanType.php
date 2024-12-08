<?php

namespace App\Form;

use App\Entity\HotelsInBhutan;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class HotelsInBhutanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hotel_name', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Hotel name cannot be blank.'])
                ]
            ])
            ->add('ratings', ChoiceType::class, [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5'
                ]
            ])
            ->add('room_type', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Room type cannot be blank.'])
                ]
            ])
            ->add('no_of_rooms', NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'No of rooms cannot be blank.'])
                ]
            ])
            ->add('room_details', TinymceType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Room details cannot be blank.'])
                ]
            ])
            ->add('ammenities', TinymceType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Ammenities cannot be blank.'])
                ]
            ])
            ->add('phone_no', NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Phone no cannot be blank.'])
                ]
            ])
            ->add('email', EmailType::class , [
                'constraints' => [
                    new Email(['message' => 'Please provide valid email.']),
                    new NotBlank(['message' => 'Email cannot be blank.'])
                ]
            ])
            ->add('website', TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'URL cannot be blank.'])
                ]
            ])
            ->add('address', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Address cannot be blank.'])
                ]
            ])
            ->add('images', ImageType::class, [
                'attr' => [
                    'image' => $options['data']->getImages()
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
