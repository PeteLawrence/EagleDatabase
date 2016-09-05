<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class MembershipTypePeriodType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fromDate', DateType::class, [ 'required' => true, 'widget' => 'single_text'])
            ->add('toDate', DateType::class, [ 'required' => true, 'widget' => 'single_text'])
            ->add('price', MoneyType::class, [ 'currency' => 'GBP' ])
            ->add('membershipType', EntityType::class, ['class' => 'AppBundle:MembershipType', 'choice_label' => 'type'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MembershipTypePeriod'
        ));
    }
}
