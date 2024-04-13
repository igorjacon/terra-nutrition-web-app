<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\Extension\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('height')
            ->add('weight')
            ->add('dob')
            ->add('goalWeight')
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