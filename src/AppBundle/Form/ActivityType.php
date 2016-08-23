<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('activityStart', DateTimeType::class, [ 'html5' => true, 'date_widget' => 'single_text', 'time_widget' => 'single_text' ])
            ->add('activityEnd', DateTimeType::class, [ 'html5' => true, 'date_widget' => 'single_text', 'time_widget' => 'single_text' ])
            ->add('spaces')
            ->add('signupStart', DateTimeType::class, [ 'html5' => true, 'date_widget' => 'single_text', 'time_widget' => 'single_text' ])
            ->add('signupEnd', DateTimeType::class, [ 'html5' => true, 'date_widget' => 'single_text', 'time_widget' => 'single_text' ])
            ->add('activityType')
            ->add('organiser')
            ->add('location')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Activity'
        ));
    }
}
