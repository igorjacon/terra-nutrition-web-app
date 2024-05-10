<?php

namespace App\Form;

use App\Entity\Meal;
use App\Entity\MealOption;
use App\Entity\MealPlan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('time', TextType::class, [
                'label' => 'form.label.time',
                'default' => '07:00',
                'attr' => [
                    'data-toggle' => 'time'
                ]
            ])
            ->add('type')
            ->add('options', CollectionType::class, [
                'entry_type' => MealOptionType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__option__',
                'by_reference' => false,
                'error_bubbling' => false,
                'default' => [new MealOption()]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meal::class,
            'translation_domain' => 'form',
            'label' => false
        ]);
    }
}
