<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MembershipPeriodType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fromDate', DateType::class, [ 'required' => true, 'format' => 'd MMMM y', 'widget' => 'choice'])
            ->add('toDate', DateType::class, [ 'required' => true, 'format' => 'd MMMM y', 'widget' => 'choice'])
            ->add('signupFromDate', DateType::class, [ 'required' => true, 'format' => 'd MMMM y', 'widget' => 'choice'])
            ->add('signupToDate', DateType::class, [ 'required' => true, 'format' => 'd MMMM y', 'widget' => 'choice'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MembershipPeriod'
        ));
    }
}
