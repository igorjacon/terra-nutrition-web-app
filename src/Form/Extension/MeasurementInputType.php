<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementInputType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('measurement', TextType::class)
            ->add('measurementType', ChoiceType::class, [
                'choices' => $options['choices'],
                'expanded' => false,
                'multiple' => false,
                'label' => false,
                'choice_translation_domain' => false,
//                'attr' => [
//                    'class' => 'form-select',
//                    'data-choices' => ''
//                ]
            ])
            ->addModelTransformer(new CallbackTransformer(
                function ($measurement) {
                    if ($measurement == null) {
                        return;
                    }
                    $measurement = explode(" ", $measurement, 2);

                    $result = [];
                    $result['measurement'] = $measurement[0];
                    $result['measurementType'] = $measurement[1];

                    return $result;
                },
                function ($measurementArray) {
                    $formattedValue = trim(str_replace(" ", "", $measurementArray['measurement']));
                    $formattedMeasurement = [
                        'measurement' => $formattedValue,
                        'measurementType' => $measurementArray['measurementType']
                    ];

                    return implode(' ', $formattedMeasurement);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'compound' => true,
            'choices' => []
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}