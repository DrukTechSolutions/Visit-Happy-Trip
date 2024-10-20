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
        $tour_images = [];
        foreach ($options['attr']['tour_package_images'] as $key => $images) {
            $tour_images[$key] = $images->getId();
        }

        $req_tour_image_1 = $options['attr']['tour_package_images'][0] == null ? true : false;
        $req_tour_image_2 = $options['attr']['tour_package_images'][1] == null ? true : false;
        $req_tour_image_3 = $options['attr']['tour_package_images'][2] == null ? true : false;

        $builder
            ->add('tour_image_1', FileType::class, [
                'mapped' => false,
                'required' => $req_tour_image_1
            ])
            ->add('tour_image_2', FileType::class, [
                'mapped' => false,
                'required' => $req_tour_image_2
            ])
            ->add('tour_image_3', FileType::class, [
                'mapped' => false,
                'required' => $req_tour_image_3
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
