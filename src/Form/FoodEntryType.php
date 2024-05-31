<?php

namespace App\Form;

use App\Entity\FoodItem;
use App\Entity\FoodItemEntry;
use App\Entity\FoodMeasurement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('foodItem', EntityType::class, [
                'class' => FoodItem::class,
                'choice_attr' => function ($choiceValue, $key, $value) {
                    return [
                        'data-protein' => $choiceValue->getFoodItemDetails()->__get('protein'),
                        'data-carbs' => $choiceValue->getFoodItemDetails()->__get('carbohydrate'),
                        'data-fat' => $choiceValue->getFoodItemDetails()->__get('fat'),
                        'data-energy-kj' => $choiceValue->getFoodItemDetails()->__get('energyWithFibreKj')
                    ];
                },
                'attr' => [
                    'onchange' => 'calculateFoodValue(this)',
                    'class' => 'food-item'
                ]
            ])
            ->add('measurement', EntityType::class, [
                'class' => FoodMeasurement::class,
                'label' => 'form.label.measuring_unit',
                'choice_attr' => function ($choiceValue, $key, $value) {
                    return ['data-gram-quantity' => $choiceValue->getGramQuantity()];
                },
                'attr' => [
                    'onchange' => 'calculateFoodValue(this)',
                    'class' => 'measurement'
                ]
            ])
            ->add('quantity', NumberType::class, [
                'attr' => [
                    'onchange' => 'calculateFoodValue(this)',
                    'class' => 'quantity'
                ]
            ])
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
