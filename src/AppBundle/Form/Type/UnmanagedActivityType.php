<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnmanagedActivityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [ 'required' => true ])
            ->add('description')
            ->add('activityStart', DateTimeType::class, [ 'html5' => true, 'date_widget' => 'single_text', 'time_widget' => 'single_text' ])
            ->add('activityEnd', DateTimeType::class, [ 'html5' => true, 'date_widget' => 'single_text', 'time_widget' => 'single_text' ])
            ->add('spaces', NumberType::class)
            ->add('people', NumberType::class)
            ->add('disabled', NumberType::class)
            ->add('activityType', EntityType::class, ['class' => 'AppBundle:ActivityType', 'choice_label' => 'type' ])
            //->add('location')
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
