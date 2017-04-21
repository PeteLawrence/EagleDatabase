<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OtherChargeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', MoneyType::class, [ 'currency' => 'GBP' ])
            ->add('description', TextType::class)
            ->add('createddatetime', DateTimeType::class)
            ->add('duedatetime', DateTimeType::class)
            ->add('paiddatetime', DateTimeType::class)
            ->add('paid', CheckboxType::class, [ 'required' => false ])
            ->add('paymentType', EntityType::class, [ 'class' => 'AppBundle:PaymentType', 'choice_label' => 'type', 'placeholder' => '', 'required' => false ])
            ->add('reference', TextType::class, [ 'required' => false ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Charge'
        ));
    }
}
