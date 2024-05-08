<?php

namespace App\Form;

use App\Entity\FoodItemDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodItemDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('energyWithFibreKj', NumberType::class, [
                'label' => 'form.label.energy_with_fibre',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'kj'
                ]
            ])
            ->add('energyWithoutFibreKj', NumberType::class, [
                'label' => 'form.label.energy_without_fibre',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'kj'
                ]
            ])
            ->add('water', NumberType::class, [
                'label' => 'form.label.water',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'ml'
                ]
            ])
            ->add('protein', NumberType::class, [
                'label' => 'form.label.protein',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('carbohydrate', NumberType::class, [
                'label' => 'form.label.carbohydrate',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('nitrogen', NumberType::class, [
                'label' => 'form.label.nitrogen',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('fat', NumberType::class, [
                'label' => 'form.label.fat',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('ash', NumberType::class, [
                'label' => 'form.label.ash',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('fibre', NumberType::class, [
                'label' => 'form.label.fibre',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('alcohol', NumberType::class, [
                'label' => 'form.label.alcohol',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('fructose', NumberType::class, [
                'label' => 'form.label.fructose',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('glucose', NumberType::class, [
                'label' => 'form.label.glucose',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('sucrose', NumberType::class, [
                'label' => 'form.label.sucrose',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('maltose', NumberType::class, [
                'label' => 'form.label.maltose',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('lactose', NumberType::class, [
                'label' => 'form.label.lactose',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('galactose', NumberType::class, [
                'label' => 'form.label.galactose',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('maltotrios', NumberType::class, [
                'label' => 'form.label.maltotrios',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('totalSugar', NumberType::class, [
                'label' => 'form.label.sugar',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('calcium', NumberType::class, [
                'label' => 'form.label.calcium',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('iron', NumberType::class, [
                'label' => 'form.label.iron',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('leadPb', NumberType::class, [
                'label' => 'form.label.lead',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('magnesium', NumberType::class, [
                'label' => 'form.label.magnesium',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('manganese', NumberType::class, [
                'label' => 'form.label.manganese',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('phosphorus', NumberType::class, [
                'label' => 'form.label.phosphorus',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('potassium', NumberType::class, [
                'label' => 'form.label.potassium',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('selenium', NumberType::class, [
                'label' => 'form.label.selenium',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('sodium', NumberType::class, [
                'label' => 'form.label.sodium',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('vitaminAretinol', NumberType::class, [
                'label' => 'form.label.vitamin_a_retinol',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('vitaminB1thiamin', NumberType::class, [
                'label' => 'form.label.vitamin_b_thiamin',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('vitaminB2riboflavin', NumberType::class, [
                'label' => 'form.label.vitamin_b2_riboflavin',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('vitaminB3niacin', NumberType::class, [
                'label' => 'form.label.vitamin_b3_niacin',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('vitaminB6pyridoxine', NumberType::class, [
                'label' => 'form.label.vitamin_b6_pyridoxine',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('vitaminB12cobalamin', NumberType::class, [
                'label' => 'form.label.vitamin_b12_cobalamin',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('vitaminC', NumberType::class, [
                'label' => 'form.label.vitamin_c',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('vitaminE', NumberType::class, [
                'label' => 'form.label.vitamin_e',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('vitaminB7biotin', NumberType::class, [
                'label' => 'form.label.vitamin_b7_biotin',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('saturatedFattyAcids', NumberType::class, [
                'label' => 'form.label.saturated_fatty_acids',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('monoSaturatedFattyAcids', NumberType::class, [
                'label' => 'form.label.monosaturated_fatty_acids',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('polySaturatedFattyAcids', NumberType::class, [
                'label' => 'form.label.polysaturated_fatty_acids',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('zinc', NumberType::class, [
                'label' => 'form.label.zinc',
                'scale' => 2,
                'attr' => [
                    'data-append' => 'g'
                ]
            ])
            ->add('classification', HiddenType::class)
            ->add('foodName', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FoodItemDetails::class,
            'translation_domain' => 'form',
            'required' => false,
            'allow_extra_fields' => true
        ]);
    }
}