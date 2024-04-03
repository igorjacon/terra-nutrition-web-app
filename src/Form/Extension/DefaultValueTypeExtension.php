<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultValueTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (null !== $options['default']) {
            $default = $options['default'];

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($default) {
                if ($event->getData() instanceof \Traversable) {
                    if (!count($event->getData())) {
                        $event->setData($default);
                    }
                } else {
                    if (!$event->getData()) {
                        $event->setData($default);
                    }
                }
            });
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'default' => null,
                'translation_domain' => 'form'
            )
        );
    }

    public function getExtendedType()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\FormType';
    }

    public static function getExtendedTypes(): iterable
    {
        return ['Symfony\Component\Form\Extension\Core\Type\FormType'];
    }
}