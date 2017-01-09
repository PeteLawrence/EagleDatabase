<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DateRangeSelectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fromDate', DateType::class, [ 'data' => new \DateTime(), 'format' => 'd MMMM y', 'widget' => 'choice'])
        ->add('toDate', DateType::class, [ 'data' => new \DateTime(), 'format' => 'd MMMM y', 'widget' => 'choice'])
        ->getForm();
    }
}
