<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SiteSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'form.label.site_name'
            ])
            ->add('displayName', CheckboxType::class, [
                'label' => 'form.label.display_name'
            ])
            ->add('logoFile', VichImageType::class, [
                'label' => 'form.label.logo',
                'required' => true,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'translation_domain' => 'form'
            ])
            ->add('favicon', FileType::class, [
                'label' => 'form.label.favicon',
                'required' => true,
                'data_class' => null,
                'help' => 'form.help.settings.favicon'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
            'translation_domain' => 'form'
        ]);
    }
}
