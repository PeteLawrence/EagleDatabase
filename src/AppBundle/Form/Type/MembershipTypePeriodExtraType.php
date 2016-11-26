<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class MembershipTypePeriodExtraType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', MoneyType::class, [ 'currency' => 'GBP' ])
            ->add('membershipTypePeriod', EntityType::class, [
                'class' => 'AppBundle:MembershipTypePeriod',
                'choice_label' => function ($a) {
                    return sprintf(
                        '%s - %s - %s',
                        $a->getMembershipType()->getType(),
                        $a->getMembershipPeriod()->getFromDate()->format('d M Y'),
                        $a->getMembershipPeriod()->getToDate()->format('d M Y')
                    );
                }
            ])
            ->add('membershipExtra', EntityType::class, [
                'class' => 'AppBundle:MembershipExtra',
                'choice_label' => function ($a) {
                    return $a->getName();
                }
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MembershipTypePeriodExtra'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_membershiptypeperiodextra';
    }
}
