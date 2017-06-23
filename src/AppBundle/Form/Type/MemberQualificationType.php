<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Entity\Person;

class MemberQualificationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qualification', EntityType::class, [
                'class' => 'AppBundle:Qualification',
                'choice_label' => 'name'
            ])
            ->add('reference', TextType::class, [
                'attr' => [
                    'placeholder' => 'eg. Certificate Number',
                ],
                'required' => false
            ])
            ->add('validFrom', DateType::class, [
                'html5' => true,
                'widget' => 'choice',
                'format' => 'd MMMM y',
                'years' => range(1975, 2030)
            ])
            ->add('validTo', DateType::class, [
                'html5' => true,
                'widget' => 'choice',
                'format' => 'd MMMM y',
                'label' => 'Valid to (if applicable)',
                'years' => range(1975, 2030),
                'required' => false
            ])
            ->add('notes', TextareaType::class, [
                'attr' => [
                    'rows' => 5
                ],
                'required' => false
            ])
            ->add('verifiedBy', EntityType::class, [
                'class' => 'AppBundle:Person',
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('verifiedDateTime', DateType::class, [
                'html5' => true,
                'widget' => 'choice',
                'format' => 'd MMMM y',
                'required' => false,
                'years' => range(2017, 2030)
            ])
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
