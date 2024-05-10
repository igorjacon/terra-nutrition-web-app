<?php

namespace App\Form;

use App\Entity\FoodItemEntry;
use App\Entity\MealOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('foodItemEntries', CollectionType::class, [
                'entry_type' => FoodEntryType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__foodEntry__',
                'by_reference' => false,
                'error_bubbling' => false,
            ])
            ->add('description')
            ->add('notes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MealOption::class,
            'translation_domain' => 'form'
        ]);
    }
}
