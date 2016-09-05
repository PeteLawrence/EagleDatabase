<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class MemberRegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('person', EntityType::class, ['class' => 'AppBundle:Person', 'choice_label' => function ($a) { return $a->getForename() . ' ' . $a->getSurname(); }, ])
            ->add('membershipTypePeriod', EntityType::class, ['class' => 'AppBundle:MembershipTypePeriod', 'choice_label' => function ($a) { return sprintf('%s %s → %s £%s', $a->getMembershipType()->getType(), $a->getFromDate()->format('d-m-Y'), $a->getToDate()->format('d-m-Y'), $a->getPrice()); } ])

        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MemberRegistration'
        ));
    }
}
