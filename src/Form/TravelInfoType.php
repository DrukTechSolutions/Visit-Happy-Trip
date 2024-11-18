<?php

namespace App\Form;

use App\Entity\TravelInfo;
use App\Entity\TravelInfoCategory;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TravelInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('travel_info_title', TextType::class, [
                'label' => 'Travel Info Title'
            ])
            ->add('travel_info_description', TinymceType::class, [
                'label' => 'Travel Info Description'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TravelInfo::class,
        ]);
    }
}
