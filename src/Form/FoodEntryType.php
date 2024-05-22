<?php

namespace App\Form;

use App\Entity\FoodItem;
use App\Entity\FoodItemEntry;
use App\Entity\FoodMeasurement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('foodItem', EntityType::class, [
                'class' => FoodItem::class,
                'attr' => [
                    'class' => 'form-select',
                    'data-choices' => ''
                ]
            ])
            ->add('measurement', EntityType::class, [
                'class' => FoodMeasurement::class,
                'label' => 'form.label.measuring_unit',
                'attr' => [
                    'class' => 'form-select',
                    'data-choices' => ''
                ]
            ])
            ->add('quantity')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FoodItemEntry::class,
            'translation_domain' => 'form',
            'label' => false
        ]);
    }
}
