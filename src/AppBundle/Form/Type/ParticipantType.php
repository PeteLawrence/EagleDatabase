<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Person;

class ParticipantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('person', EntityType::class, ['class' => 'AppBundle:Person', 'choice_label' => function (Person $a) { return $a->getForename() . ' ' . $a->getSurname(); }, ])
            ->add('participantStatus', EntityType::class, ['class' => 'AppBundle:ParticipantStatus', 'choice_label' => 'status', 'label' => 'Status'])
            ->add('notes', TextareaType::class, [ 'attr' => ['rows' => '5'], 'required' => false ])
            //->add('activity', EntityType::class, ['class' => 'AppBundle:Activity', 'choice_label' => 'name'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Participant'
        ));
    }
}
