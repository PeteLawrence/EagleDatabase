<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use AppBundle\Entity\MembershipPeriod;

class MembershipTypePeriodType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('membershipType', EntityType::class, ['class' => 'AppBundle:MembershipType', 'choice_label' => 'type'])
            ->add('membershipPeriod', EntityType::class, ['class' => 'AppBundle:MembershipPeriod', 'choice_label' => function (MembershipPeriod $a) {
                return sprintf('%s → %s', $a->getFromDate()->format('d/m/Y'), $a->getToDate()->format('d/m/Y'));
            }])
            ->add('price', MoneyType::class, [ 'currency' => 'GBP' ])
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
