<?php

namespace App\Form;

use App\Entity\Phone;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                'error_bubbling' => false,
                'default' => [new Phone()]
            ])
            ->add('address', AddressType::class, [
                'label' => false
            ])
            ->add('email')
            ->add('enabled', CheckboxType::class)
            ->add('profileFile', VichImageType::class, [
                'label' => 'form.label.profile_img',
                'required' => true,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => true,
                'translation_domain' => 'form'
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $options['role'] ? [$options['role'] => User::ROLES_ALLOWED[$options['role']]] : User::ROLES_ALLOWED,
                'multiple' => true,
                'expanded' => true,
                'default' => $options['role'] ? [User::ROLES_ALLOWED[$options['role']]] : [],
                'choice_label' => function ($key, $value) {
                    return 'form.choice.' . $value;
                },
                'label_attr' => [
                    'class' => 'checkbox-custom',
                ],
                'label' => 'form.label.roles',
            ])
            ->add('authCode')
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $user = $event->getData();
                if ($user) {
                    $form->add('enabled', CheckboxType::class);
                } else {
                    $form->add('enabled', CheckboxType::class, ['default' => true]);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'form',
            'role' => null
        ]);
    }
}
