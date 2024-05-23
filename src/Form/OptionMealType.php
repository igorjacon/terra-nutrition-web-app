<?php

namespace App\Form;

use App\Entity\MealOption;
use App\Entity\Professional;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OptionMealType extends AbstractType
{
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $mealOption = $builder->getData();
        $professional = $mealOption->getProfessional();
        $builder
            ->add('name')
            ->add('foodItemEntries', CollectionType::class, [
                'entry_type' => FoodEntryType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'error_bubbling' => false,
                'default' => []
            ])
            ->add('description')
            ->add('notes')
        ;
        if (!$professional or $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder->add('professional', EntityType::class, [
                'class' => Professional::class,
                'label' => 'form.label.professional'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MealOption::class,
            'translation_domain' => 'form'
        ]);
    }
}