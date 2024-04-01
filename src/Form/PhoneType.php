<?php

namespace App\Form;

use App\Entity\Phone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prefix', HiddenType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'w-25 border-0'
                ]
            ])
            ->add('number', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control rounded-end flag-input',
                    'placeholder' => 'Enter number'
                ]
            ])
            ->add('flag', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Phone::class,
            'label' => false,
            'translation_domain' => 'form'
        ]);
    }
}