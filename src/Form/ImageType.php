<?php

namespace App\Form;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $req_image_1 = $options['attr']['image'][0] == null ? true : false;
        $req_image_2 = $options['attr']['image'][1] == null ? true : false;
        $req_image_3 = $options['attr']['image'][2] == null ? true : false;

        $builder
            ->add('image_1', FileType::class, [
                'mapped' => false,
                'required' => $req_image_1
            ])
            ->add('image_2', FileType::class, [
                'mapped' => false,
                'required' => $req_image_2
            ])
            ->add('image_3', FileType::class, [
                'mapped' => false,
                'required' => $req_image_3
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}
