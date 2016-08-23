<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('forename')
            ->add('surname')
            ->add('email')
            //->add('password')
            ->add('admin')
            ->add('gender')
            ->add('dob', DateType::class, [ 'html5' => true, 'widget' => 'single_text', 'label' => 'Date of Birth' ])
            ->add('addr1')
            ->add('addr2')
            ->add('town')
            ->add('county')
            ->add('postcode')
            ->add('telephone')
            ->add('mobile')
            ->add('disability')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Person'
        ));
    }
}
