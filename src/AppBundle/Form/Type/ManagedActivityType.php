<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('description', TextareaType::class, [ 'attr' => [ 'rows' => 8] ])
            ->add('activityStart', DateTimeType::class, [ 'required' => true, 'date_format' => 'd MMMM y', 'date_widget' => 'choice', 'time_widget' => 'choice' ])
            ->add('activityEnd', DateTimeType::class, [ 'required' => true, 'date_format' => 'd MMMM y', 'date_widget' => 'choice', 'time_widget' => 'choice' ])
            ->add('spaces')
            ->add('allowOnlineSignup', CheckboxType::class)
            ->add('signupStart', DateTimeType::class, [ 'required' => false, 'date_format' => 'd MMMM y', 'date_widget' => 'choice', 'time_widget' => 'choice' ])
            ->add('signupEnd', DateTimeType::class, [ 'required' => false, 'date_format' => 'd MMMM y', 'date_widget' => 'choice', 'time_widget' => 'choice' ])
            ->add('activityType', EntityType::class, ['class' => 'AppBundle:ActivityType', 'choice_label' => 'type' ])
            ->add('organiser', EntityType::class, ['class' => 'AppBundle:Person', 'choice_label' => function (Person $a) { return $a->getForename() . ' ' . $a->getSurname(); }, ])
            ->add('startLocation', EntityType::class, ['class' => 'AppBundle:Location', 'choice_label' => 'name' ])
            ->add('endLocation', EntityType::class, ['class' => 'AppBundle:Location', 'choice_label' => 'name' ])
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
