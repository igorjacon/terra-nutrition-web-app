<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeImmutableToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\DateTimeToLocalizedStringTransformer;

class DatePickerType extends AbstractType
{
    const DEFAULT_FORMAT = \IntlDateFormatter::MEDIUM;
    const HTML5_FORMAT = 'yyyy-MM-dd';

    private static $acceptedFormats = [
        \IntlDateFormatter::FULL,
        \IntlDateFormatter::LONG,
        \IntlDateFormatter::MEDIUM,
        \IntlDateFormatter::SHORT,
    ];

    private static $widgets = [
        'text' => 'Symfony\Component\Form\Extension\Core\Type\TextType',
        'choice' => 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateFormat = \is_int($options['format']) ? $options['format'] : self::DEFAULT_FORMAT;
        $timeFormat = \IntlDateFormatter::NONE;
        $calendar = \IntlDateFormatter::GREGORIAN;
        $pattern = \is_string($options['format']) ? $options['format'] : null;

        if (!\in_array($dateFormat, self::$acceptedFormats, true)) {
            throw new InvalidOptionsException('The "format" option must be one of the IntlDateFormatter constants (FULL, LONG, MEDIUM, SHORT) or a string representing a custom format.');
        }

        if ('single_text' === $options['widget']) {
            if (null !== $pattern && false === strpos($pattern, 'y') && false === strpos($pattern, 'M') && false === strpos($pattern, 'd')) {
                throw new InvalidOptionsException(sprintf('The "format" option should contain the letters "y", "M" or "d". Its current value is "%s".', $pattern));
            }

            $builder->addViewTransformer(new DateTimeToLocalizedStringTransformer(
                $options['model_timezone'],
                $options['view_timezone'],
                $dateFormat,
                $timeFormat,
                $calendar,
                $pattern
            ));
        } else {
            if (null !== $pattern && (false === strpos($pattern, 'y') || false === strpos($pattern, 'M') || false === strpos($pattern, 'd'))) {
                throw new InvalidOptionsException(sprintf('The "format" option should contain the letters "y", "M" and "d". Its current value is "%s".', $pattern));
            }

            $yearOptions = $monthOptions = $dayOptions = [
                'error_bubbling' => true,
                'empty_data' => '',
            ];
            // when the form is compound the entries of the array are ignored in favor of children data
            // so we need to handle the cascade setting here
            $emptyData = $builder->getEmptyData() ?: [];

            if (isset($emptyData['year'])) {
                $yearOptions['empty_data'] = $emptyData['year'];
            }
            if (isset($emptyData['month'])) {
                $monthOptions['empty_data'] = $emptyData['month'];
            }
            if (isset($emptyData['day'])) {
                $dayOptions['empty_data'] = $emptyData['day'];
            }

            if (isset($options['invalid_message'])) {
                $dayOptions['invalid_message'] = $options['invalid_message'];
                $monthOptions['invalid_message'] = $options['invalid_message'];
                $yearOptions['invalid_message'] = $options['invalid_message'];
            }

            if (isset($options['invalid_message_parameters'])) {
                $dayOptions['invalid_message_parameters'] = $options['invalid_message_parameters'];
                $monthOptions['invalid_message_parameters'] = $options['invalid_message_parameters'];
                $yearOptions['invalid_message_parameters'] = $options['invalid_message_parameters'];
            }

            $formatter = new \IntlDateFormatter(
                \Locale::getDefault(),
                $dateFormat,
                $timeFormat,
                // see https://bugs.php.net/bug.php?id=66323
                class_exists('IntlTimeZone', false) ? \IntlTimeZone::createDefault() : null,
                $calendar,
                $pattern
            );

            // new \IntlDateFormatter may return null instead of false in case of failure, see https://bugs.php.net/bug.php?id=66323
            if (!$formatter) {
                throw new InvalidOptionsException(intl_get_error_message(), intl_get_error_code());
            }

            $formatter->setLenient(false);

            if ('choice' === $options['widget']) {
                // Only pass a subset of the options to children
                $yearOptions['choices'] = $this->formatTimestamps($formatter, '/y+/', $this->listYears($options['years']));
                $yearOptions['placeholder'] = $options['placeholder']['year'];
                $yearOptions['choice_translation_domain'] = $options['choice_translation_domain']['year'];
                $monthOptions['choices'] = $this->formatTimestamps($formatter, '/[M|L]+/', $this->listMonths($options['months']));
                $monthOptions['placeholder'] = $options['placeholder']['month'];
                $monthOptions['choice_translation_domain'] = $options['choice_translation_domain']['month'];
                $dayOptions['choices'] = $this->formatTimestamps($formatter, '/d+/', $this->listDays($options['days']));
                $dayOptions['placeholder'] = $options['placeholder']['day'];
                $dayOptions['choice_translation_domain'] = $options['choice_translation_domain']['day'];
            }

            // Append generic carry-along options
            foreach (['required', 'translation_domain'] as $passOpt) {
                $yearOptions[$passOpt] = $monthOptions[$passOpt] = $dayOptions[$passOpt] = $options[$passOpt];
            }

            $builder
                ->add('year', self::$widgets[$options['widget']], $yearOptions)
                ->add('month', self::$widgets[$options['widget']], $monthOptions)
                ->add('day', self::$widgets[$options['widget']], $dayOptions)
                ->addViewTransformer(new DateTimeToArrayTransformer(
                    $options['model_timezone'], $options['view_timezone'], ['year', 'month', 'day']
                ))
                ->setAttribute('formatter', $formatter)
            ;
        }

        if ('datetime_immutable' === $options['input']) {
            $builder->addModelTransformer(new DateTimeImmutableToDateTimeTransformer());
        } elseif ('string' === $options['input']) {
            $builder->addModelTransformer(new ReversedTransformer(
                new DateTimeToStringTransformer($options['model_timezone'], $options['model_timezone'], 'Y-m-d')
            ));
        } elseif ('timestamp' === $options['input']) {
            $builder->addModelTransformer(new ReversedTransformer(
                new DateTimeToTimestampTransformer($options['model_timezone'], $options['model_timezone'])
            ));
        } elseif ('array' === $options['input']) {
            $builder->addModelTransformer(new ReversedTransformer(
                new DateTimeToArrayTransformer($options['model_timezone'], $options['model_timezone'], ['year', 'month', 'day'])
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (\Locale::getDefault() == 'en') {
            $locale = mb_strtolower(str_replace('_', '-', \Locale::getDefault())) . '-gb';
        } else {
            $locale = mb_strtolower(str_replace('_', '-', \Locale::getDefault()));
        }
        $view->vars['widget'] = $options['widget'];
        $view->vars['attr']['data-toggle'] = 'datetimepicker';
        $view->vars['attr']['data-date-format'] = 'L';
        $view->vars['attr']['data-date-locale'] = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $pattern = 'd/m/Y';

        $resolver->setDefaults([
            'widget' => 'single_text',
            'translation_domain' => 'form',
            'html5' => false,
            'format' => $pattern,
            'model_timezone' => null,
            'view_timezone' => null,
            'input' => 'datetime',
            'compound' => false,
        ]);
    }
}