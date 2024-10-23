<?php

namespace App\Form;

use App\Entity\Bookings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Name field should not be blank.'])
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Email field should not be blank.']),
                    new Email()
                ]
            ])
            ->add('contact_no', TelType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Contact no field should not be blank.']),
                ]
            ])
            ->add('country', CountryType::class)
            ->add('date_of_arrival', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('date_of_departure', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('no_of_adults', IntegerType::class, [
                'attr' => [
                    'min' => '1',
                    'value' => '1'
                ]
            ])
            ->add('no_of_child', IntegerType::class, [
                'attr' => [
                    'min' => '0',
                    'value' => '0'
                ]
            ])
            ->add('tour_type', ChoiceType::class , [
                'choices' => [
                    'Solo' => 'solo',
                    'Family' => 'family',
                    'Friend' => 'friend',
                ]
            ])
            ->add('tour_packages', ChoiceType::class, [
                'choices' => [
                    'Cultural Tour' => 'cultural-tour',
                    'Festival Tour' => 'festival-tour',
                    'Adventure Tour' => 'adventure-tour',
                    'Trekking Tour' => 'trekking-tour',
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => '5'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Message field should not be blank.'])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bookings::class,
        ]);
    }
}
