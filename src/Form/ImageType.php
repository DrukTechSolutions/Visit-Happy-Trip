<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constraints = [];

        if ($options['attr']['images'][0] == null) {
            $constraints = [ new NotBlank(['message' => 'Image 1 cannot be blank.']) ];
        }
        if ($options['attr']['images'][1] == null) {
            $constraints = [ new NotBlank(['message' => 'Image 2 cannot be blank.']) ];
        }
        if ($options['attr']['images'][2] == null) {
            $constraints = [ new NotBlank(['message' => 'Image 3 cannot be blank.']) ];
        }

        $builder
            ->add('image_1', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => $constraints
            ])
            ->add('image_2', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => $constraints
            ])
            ->add('image_3', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => $constraints
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
