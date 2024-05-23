<?php

namespace App\Form;

use App\Entity\FoodMeasurement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodMeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('abbreviation')
            ->add('gram_quantity', NumberType::class, [
                'label' => 'form.label.quantity_in_g'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FoodMeasurement::class,
            'translation_domain' => 'form'
        ]);
    }
}
