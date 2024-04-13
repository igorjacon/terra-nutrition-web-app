<?php

namespace App\Form;

use App\Entity\Customer;
use App\Form\Extension\MeasurementInputType;
use App\Utils\Measurements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', UserType::class, [
                'role' => $options['role']
            ])
            ->add('height', MeasurementInputType::class, [
                'choices' => Measurements::HEIGHT_CHOICES
            ])
            ->add('weight', MeasurementInputType::class, [
                'choices' => Measurements::WEIGHT_CHOICES
            ])
            ->add('dob')
            ->add('goalWeight', MeasurementInputType::class, [
                'choices' => Measurements::WEIGHT_CHOICES
            ])
            ->add('occupation')
            ->add('dietaryPreference')
            ->add('goals')
            ->add('reasonSeekProfessional')
            ->add('currExerciseRoutine')
            ->add('medicalInfo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'translation_domain' => 'form',
            'role' => null
        ]);
    }
}
