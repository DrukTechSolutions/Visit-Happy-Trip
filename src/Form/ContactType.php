<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'mapped' => 'false',
                'attr' => [
                    'label' => 'Your Name'
                ]
            ])
            ->add('email', EmailType::class, [
                'mapped' => 'false',
                'attr' => [
                    'label' => 'Email'
                ]
            ])
            ->add('subject', TextType::class, [
                'mapped' => 'false',
                'attr' => [
                    'label' => 'Subject'
                ]
            ])
            ->add('message', TextareaType::class, [
                'mapped' => 'false',
                'attr' => [
                    'label' => 'Message'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
