<?php

namespace App\Form;

use App\Entity\CustomerMeasurement;
use App\Form\Extension\DatePickerType;
use App\Form\Extension\MeasurementInputType;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerMeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $customer = $builder->getData()->getCustomer();

        $gender = $customer->getGender();
        $age = $this->getAge($customer->getDob());

        $customerHeight = $customer->getHeight();
        if ($customer->getHeight()) {
            $heightMeasurement = explode(" ", $customerHeight, 2);

            $height = floatval(str_replace(",", ".", $heightMeasurement[0]));
            if ($heightMeasurement[1] === "cm") {
                if ($height > 100) {
                    $height = $height/100;
                    $customerHeight = $height . " m";
                }
            }
        }

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
                'default' => $customerHeight,
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
                'mapped' => false,
                'attr' => [
                    'onchange' => 'updateBmiResults("'. $gender .'",' . $age . ')'
                ]
            ])
            ->add('chest', NumberType::class, [
                'label' => 'form.label.chest',
                'required' => false,
                'html5' => true,
                'attr' => [
                    'onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')',
                    'data-append' => 'mm'
                ]
            ])
            ->add('abdomen', NumberType::class, [
                'label' => 'form.label.abdomen',
                'required' => false,
                'html5' => true,
                'attr' => [
                    'onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')',
                    'data-append' => 'mm'
                ]
            ])
            ->add('thigh', NumberType::class, [
                'label' => 'form.label.thigh',
                'required' => false,
                'html5' => true,
                'attr' => [
                    'onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')',
                    'data-append' => 'mm'
                ]
            ])
            ->add('triceps', NumberType::class, [
                'label' => 'form.label.triceps',
                'required' => false,
                'html5' => true,
                'attr' => [
                    'onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')',
                    'data-append' => 'mm'
                ]
            ])
            ->add('biceps', NumberType::class, [
                'label' => 'form.label.biceps',
                'required' => false,
                'html5' => true,
                'attr' => [
                    'onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')',
                    'data-append' => 'mm'
                ]
            ])
            ->add('suprailiac', NumberType::class, [
                'label' => 'form.label.suprailiac',
                'required' => false,
                'html5' => true,
                'attr' => [
                    'onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')',
                    'data-append' => 'mm'
                ]
            ])
            ->add('subscapular', NumberType::class, [
                'label' => 'form.label.subscapular',
                'required' => false,
                'html5' => true,
                'attr' => [
                    'onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')',
                    'data-append' => 'mm'
                ]
            ])
            ->add('midaxillary', NumberType::class, [
                'label' => 'form.label.midaxillary',
                'html5' => true,
                'required' => false,
                'attr' => [
                    'onblur' => 'updateBmiResults("'. $gender .'",' . $age . ')',
                    'data-append' => 'mm'
                ]
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
