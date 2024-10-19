<?php

namespace App\Form;

use App\Entity\TopDestination;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopDestinationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destination_title', TextType::class, [
                'label' => 'Destination Title'
            ])
            ->add('description', TinymceType::class)
            ->add('destination_image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Destination Image'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TopDestination::class,
        ]);
    }
}
