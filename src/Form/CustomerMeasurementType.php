<?php

namespace App\Form;

use App\Entity\CustomerMeasurement;
use App\Form\Extension\DatePickerType;
use App\Form\Extension\MeasurementInputType;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerMeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $customer = $builder->getData()->getCustomer();

        $gender = $customer->getGender();
        $age = $this->getAge($customer->getDob());

        $builder
            ->add('description')
            ->add('createdAt', DatePickerType::class, [
                'label' => 'form.label.date',
            ])
            ->add('height', MeasurementInputType::class, [
                'choices' => ['m'  => 'm'],
                'choice_attr' => [
                    'data-disabled' => ''
                ],
                'default' => $customer->getHeight(),
                'attr' => ['onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')']
            ])
            ->add('currWeight', MeasurementInputType::class, [
                'choices' => ['kg' => 'kg'],
                'choice_attr' => [
                    'data-disabled' => ''
                ],
                'default' => $customer->getWeight(),
                'attr' => ['onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')']
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
            ->add('method', ChoiceType::class, [
                'label' => 'form.label.method',
                'choices' => array_flip([
                    'jackson_pollock_3' => '3 prong - Jackson & Pollock',
                    'guedes_3'          => '3 prong - Guedes',
                    'durin_womersley_4' => '4 prong - Durnin & Womersley',
                    'faulkner_4'        => '4 prong - Faulkner',
                    'jackson_pollock_7' => '7 prong - Jackson, Pollock & Ward'
                ]),
                'mapped' => false
            ])
            ->add('chest', null, [
                'label' => 'form.label.chest',
                'required' => false,
                'attr' => ['onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')']
            ])
            ->add('abdomen', null, [
                'label' => 'form.label.abdomen',
                'required' => false,
                'attr' => ['onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')']
            ])
            ->add('thigh', null, [
                'label' => 'form.label.thigh',
                'required' => false,
                'attr' => ['onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')']
            ])
            ->add('triceps', null, [
                'label' => 'form.label.triceps',
                'required' => false,
                'attr' => ['onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')']
            ])
            ->add('suprailiac', null, [
                'label' => 'form.label.suprailiac',
                'required' => false,
                'attr' => ['onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')']
            ])
            ->add('subscapular', null, [
                'label' => 'form.label.subscapular',
                'required' => false,
                'attr' => ['onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')']
            ])
            ->add('midaxillary', null, [
                'label' => 'form.label.midaxillary',
                'required' => false,
                'attr' => ['onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')']
            ])
            ->add('bmi', HiddenType::class)
            ->add('bfp', HiddenType::class)
            ->add('lfp', HiddenType::class)
            ->add('bf', HiddenType::class)
            ->add('lm', HiddenType::class)
            ->add('bodyDensity', HiddenType::class)
            ->add('sumSkinfolds', HiddenType::class)
        ;
    }

    private function getAge($dob) {
        $today = new DateTime();
        $age = $dob->diff($today)->y;
        return $age;
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
