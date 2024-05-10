<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\MealPlan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealPlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class, [
                'label' => 'form.label.description',
                'required' => false,
                'attr' => [
                    'rows' => 3
                ]
            ])
            ->add('days', ChoiceType::class, [
                'choices' => array_flip(MealPlan::DAYS),
                'label' => 'form.label.meal_days',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'choice_translation_domain' => 'form',
                'label_attr' => [
                    'class' => 'mb-0'
                ]
            ])
            ->add('meals', CollectionType::class, [
                'entry_type' => MealType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype_name' => '__meal__',
                'prototype' => true,
                'by_reference' => false,
                'error_bubbling' => false
            ])
            ->add('customers', EntityType::class, [
                'class' => Customer::class,
                'by_reference' => false,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-select',
                    'data-choices' => ''
                ]
            ])
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MealPlan::class,
            'translation_domain' => 'form'
        ]);
    }
}
