<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MembershipReportFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('date', DateType::class, [ 'data' => new \DateTime(), 'widget' => 'single_text'])
        ->add('ageRanges', TextType::class, [ 'data' => '15,18,25,35,45,55,65,75'])
        ->getForm();
    }
}
