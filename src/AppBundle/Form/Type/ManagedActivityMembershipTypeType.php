<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ManagedActivityMembershipTypeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('managedActivity', EntityType::class, [
                'class' => 'AppBundle:ManagedActivity',
                'choice_label' => 'nameAndDate',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.activityStart', 'ASC');
                }
            ])
            ->add('membershipType', EntityType::class, ['class' => 'AppBundle:MembershipType', 'choice_label' => 'type' ])
            ->add('price', MoneyType::class, ['currency' => 'GBP'])
            ->add('countsTowardsSize')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ManagedActivityMembershipType'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_managedactivitymembershiptype';
    }
}
