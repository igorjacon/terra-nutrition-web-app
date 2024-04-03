<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Professional;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfessionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', UserType::class, [
                'role' => $options['role']
            ])
            ->add('website')
            ->add('jobTitle')
            ->add('taxNumber')
            ->add('locations', CollectionType::class, [
                'entry_type' => LocationType::class,
                'label' => false,
                'by_reference' => false,
                'default' => [new Location()]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
            'translation_domain' => 'form',
            'role' => null
        ]);
    }
}
