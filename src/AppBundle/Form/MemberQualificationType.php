<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MemberQualificationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('expiration', DateType::class, [ 'html5' => true, 'widget' => 'single_text' ])
            ->add('qualification', EntityType::class, ['class' => 'AppBundle:Qualification', 'choice_label' => 'name' ])
            ->add('person', EntityType::class, ['class' => 'AppBundle:Person', 'choice_label' => function ($a) { return $a->getForename() . ' ' . $a->getSurname(); }, ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MemberQualification'
        ));
    }
}
