<?php

namespace App\Form;

use App\Entity\TourCategory;
use App\Entity\TourPackage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class TourPackageType extends AbstractType
{
    public function __construct(private EntityManagerInterface $em)
    {

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $subCategory = $options['data']->getTourCategory();
        $mainCategory =  $subCategory === null ? null : $subCategory->getSubCategory();
        $subCategories = $mainCategory === null ? null : $this->em->getRepository(TourCategory::class)->findBy(['sub_category' => $mainCategory->getId() ]);
        $builder
            ->add('tour_title', TextType::class, [
                'label' => 'Tour Title'
            ])
            ->add('tour_parent_category', EntityType::class, [
                'label' => 'Tour Parent Category',
                'mapped' => false,
                'required' => false,
                'class' => TourCategory::class,
                'choice_label' => 'category',
                'placeholder' => 'Select Parent Category',
                'query_builder' =>  function (EntityRepository $er) {
                    $query = $er->createQueryBuilder('c')
                            ->where('c.sub_category IS NULL')
                    ;
                    return $query;
                },
                'choice_value' => 'id',
                'data' => $mainCategory
            ])
            ->add('tourCategory', EntityType::class, [
                'label' => 'Tour Sub Category',
                'class' => TourCategory::class,
                'placeholder' => 'Select Sub Category',
                'choices' =>  $subCategories,
                'choice_label' => 'category',
                'data' => $subCategory
            ])
            ->add('price')
            ->add('tour_overview', TinymceType::class, [
                'label' => 'Tour Overview',
            ])
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

        $addSubCategory = function (FormInterface $form, ?TourCategory $tourCategory) {
            $subCategories = $tourCategory === null ? [] : $tourCategory->getTourCategories();
            $form->add('tourCategory', EntityType::class, [
                'label' => 'Tour Sub Category',
                'class' => TourCategory::class,
                'placeholder' => 'Select Sub Category',
                'choices' => $subCategories,
                'choice_label' => 'category'
            ]);
        };

        if ($options['data']->getId() === null || $subCategory === null) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($addSubCategory) {
                $data = $event->getData();
                $addSubCategory($event->getForm(), $data->getTourCategory());
            });
        }

        $builder->get('tour_parent_category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($addSubCategory) {
                $category = $event->getForm()->getData();
                $addSubCategory($event->getForm()->getParent(), $category);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TourPackage::class,
        ]);
    }
}
