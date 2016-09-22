<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Person;

class ManagedActivityType extends AbstractType
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
            ->add('activityStart', DateTimeType::class, [ 'required' => true, 'date_widget' => 'single_text', 'time_widget' => 'single_text' ])
            ->add('activityEnd', DateTimeType::class, [ 'required' => true, 'date_widget' => 'single_text', 'time_widget' => 'single_text' ])
            ->add('spaces')
            ->add('signupStart', DateTimeType::class, [ 'required' => false, 'date_widget' => 'single_text', 'time_widget' => 'single_text' ])
            ->add('signupEnd', DateTimeType::class, [ 'required' => false, 'date_widget' => 'single_text', 'time_widget' => 'single_text' ])
            ->add('activityType', EntityType::class, ['class' => 'AppBundle:ActivityType', 'choice_label' => 'type' ])
            ->add('organiser', EntityType::class, ['class' => 'AppBundle:Person', 'choice_label' => function (Person $a) { return $a->getForename() . ' ' . $a->getSurname(); }, ])
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
