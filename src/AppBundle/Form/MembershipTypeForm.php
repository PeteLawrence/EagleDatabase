<?php

namespace AppBundle\Form;

use AppBundle\Entity\MembershipType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MembershipTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('price')
            ->add('save', SubmitType::class)
        ;
    }
}
