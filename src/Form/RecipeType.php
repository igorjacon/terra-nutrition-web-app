<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Professional;
use App\Entity\Recipe;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RecipeType extends AbstractType
{
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $recipe = $builder->getData();
        $professional = $recipe->getProfessional();
        $builder
            ->add('name')
            ->add('foodItemEntries', CollectionType::class, [
                'entry_type' => FoodEntryType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
//                'by_reference' => false,
                'error_bubbling' => false,
                'default' => []
            ])
            ->add('portion')
            ->add('instructions', CKEditorType::class, [
                'label' => 'form.label.instructions',
                'attr' => [
                    'row' => 5
                ]
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
        ;
        if (!$professional or $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder->add('professional', EntityType::class, [
                'class' => Professional::class,
                'label' => 'form.label.professional',
                'attr' => [
                    'class' => 'form-select',
                    'data-choices' => ''
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'translation_domain' => 'form'
        ]);
    }
}
