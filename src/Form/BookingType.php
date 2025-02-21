<?php

namespace App\Form;

use App\Entity\Bookings;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TourCategory;
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
use Symfony\Component\Validator\Constraints\NotNull;

class BookingType extends AbstractType
{
    public function __construct(private EntityManagerInterface $em)
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
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
            ->add('country', CountryType::class, [
                'placeholder' => 'Select your country',
            ])
            ->add('date_of_arrival', DateType::class, [
                'widget' => 'single_text',
                //'data' => new \DateTime(),
                'empty_data' => null,
                'constraints' => [
                    new NotNull(['message' => 'Date of arrival field should not be blank.']),
                    new NotBlank(['message' => 'Date of arrival field should not be blank.'])
                ]
            ])
            ->add('date_of_departure', DateType::class, [
                'widget' => 'single_text',
                //'data' => new \DateTime(),
                'empty_data' => null,
                'constraints' => [
                    new NotNull(['message' => 'Date of departure field should not be blank.']),
                    new NotBlank(['message' => 'Date of departure field should not be blank.'])
                ]
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
            ->add('tour_type', ChoiceType::class, [
                'placeholder' => 'Select tour type',
                'choices' => [
                    'Solo' => 'solo',
                    'Family' => 'family',
                    'Friend' => 'friend',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Tour type field should not be blank.'])
                ]
            ])
            ->add('tour_packages', ChoiceType::class, [
                'placeholder' => 'Select tour package',
                'choices' => $this->getPackages(),
                'constraints' => [
                    new NotBlank(['message' => 'Tour package field should not be blank.'])
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => '5'
                ]
            ])
        ;
    }

    public function getPackages() {
        $mainCategory = [];
        $categories = $this->em->getRepository(TourCategory::class)->findAll();

        foreach ($categories as $category) {
            if ($category->getSubCategory() == null) {
                $mainCategory[ $category->getCategory()] = $category->getSlug();
            }
        }

        return $mainCategory;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bookings::class,
        ]);
    }
}
