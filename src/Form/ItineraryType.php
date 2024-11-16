<?php

namespace App\Form;

use App\Entity\Itinerary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ItineraryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Itinerary Name'
            ])
            ->add('itinerary_description', TinymceType::class, [
                'label' => 'Itinerary Description'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Itinerary::class,
        ]);
    }
}
