<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use AppBundle\Entity\Person;
use AppBundle\Entity\MembershipTypePeriod;

class MemberRegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('person', EntityType::class, ['class' => 'AppBundle:Person', 'choice_label' => function (Person $a) { return $a->getForename() . ' ' . $a->getSurname(); }, ])
            ->add('membershipTypePeriod', EntityType::class, ['class' => 'AppBundle:MembershipTypePeriod', 'choice_label' => function (MembershipTypePeriod $a) { return sprintf('%s %s → %s £%s', $a->getMembershipType()->getType(), $a->getMembershipPeriod()->getFromDate()->format('d-m-Y'), $a->getMembershipPeriod()->getToDate()->format('d-m-Y'), $a->getPrice()); } ])

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
