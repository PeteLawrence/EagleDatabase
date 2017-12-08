<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('forename', TextType::class, [ 'attr' => ['placeholder' => 'Forename(s)'] ])
            ->add('surname', TextType::class, [ 'attr' => ['placeholder' => 'Surname'] ])
            ->add('email', EmailType::class, [ 'attr' => ['placeholder' => 'Email Address'], 'help' => 'NB. Without an email they will not be able to use the members area, or receive club emails', 'required' => false ])
            ->add('gender', ChoiceType::class, [ 'choices' => [ 'Female' => 'F', 'Male' => 'M'] ])
            ->add('dob', BirthdayType::class, [ 'html5' => true, 'widget' => 'choice', 'format' => 'd MMMM y', 'label' => 'Date of birth' ])
            ->add('addr1', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 1'] ])
            ->add('addr2', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 2'], 'required' => false ])
            ->add('addr3', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 3'], 'required' => false ])
            ->add('town', TextType::class, [ 'attr' => ['placeholder' => 'Town'] ])
            ->add('county', TextType::class, [ 'attr' => ['placeholder' => 'County'] ])
            ->add('postcode', TextType::class, [ 'attr' => ['placeholder' => 'Postcode'] ])
            ->add('telephone', TextType::class, [ 'required' => false ])
            ->add('mobile', TextType::class, [ 'required' => false ])
            ->add('disability', CheckboxType::class, ['help' => 'Does the person consider that they have a disability?'])
            ->add('bcMembershipNumber', TextType::class, [ 'attr' => ['placeholder' => 'BC Membership Number'], 'required' => false, 'label' => 'British Canoeing Membership Number' ])
            ->add('notes', TextareaType::class, [ 'required' => false ] )
            ->add('doNotContact', CheckboxType::class, [ 'label' => 'Do Not Contact', 'required' => false, 'help' => 'Do not contact this person' ])
            ->add('admin', CheckboxType::class, [ 'required' => false ] )
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
