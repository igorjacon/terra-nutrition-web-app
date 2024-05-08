<?php

namespace App\Form;

use App\Entity\FoodItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('foodKey')
            ->add('foodItemDetails', FoodItemDetailsType::class, [
                'label' => false
            ])
            ->add('profileId')
            ->add('derivation')
            ->add('name')
            ->add('description')
            ->add('samplingDetails')
            ->add('classification')
            ->add('classificationName')
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                if (isset($data['classification'])) {
                    $data['foodItemDetails']['classification'] = $data['classification'];
                }
                if (isset($data['name'])) {
                    $data['foodItemDetails']['foodName'] = $data['name'];
                }
                $event->setData($data);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FoodItem::class,
            'translation_domain' => 'form'
        ]);
    }
}