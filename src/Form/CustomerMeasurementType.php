<?php

namespace App\Form;

use App\Entity\CustomerMeasurement;
use App\Form\Extension\DatePickerType;
use App\Form\Extension\MeasurementInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerMeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('createdAt', DatePickerType::class, [
                'label' => 'form.label.date',
            ])
            ->add('height', MeasurementInputType::class, [
                'choices' => ['m'  => 'm'],
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('currWeight', MeasurementInputType::class, [
                'choices' => ['kg' => 'kg'],
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('idealWeight', MeasurementInputType::class, [
                'choices' => ['kg' => 'kg'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('rightArmRelax', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('leftArmRelax', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('rightArmContracted', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('leftArmContracted', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('rightForearm', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('leftForearm', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('rightWrist', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('leftWrist', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('neck', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('shoulder', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('breastplate', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('waist', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('abs', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('hip', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('rightCalf', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('leftCalf', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('rightThigh', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('leftThigh', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('rightProximalThigh', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
            ->add('leftProximalThigh', MeasurementInputType::class, [
                'choices' => ['cm' => 'cm'],
                'required' => false,
                'choice_attr' => [
                    'data-disabled' => ''
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerMeasurement::class,
            'translation_domain' => 'form',
            'label' => false
        ]);
    }
}
