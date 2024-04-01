<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('phones', CollectionType::class, [
                'entry_type' => PhoneType::class,
                'label' => 'form.label.phone',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('profileFile', VichImageType::class, [
                'label' => 'form.label.profile_img',
                'required' => true,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => true,
                'translation_domain' => 'form'
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => User::ROLES_ALLOWED,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function ($key, $value) {
                    return 'form.choice.' . $value;
                },
                'label_attr' => [
                    'class' => 'checkbox-custom',
                ],
                'label' => 'form.label.roles',
            ])
            ->add('authCode')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'form'
        ]);
    }
}
