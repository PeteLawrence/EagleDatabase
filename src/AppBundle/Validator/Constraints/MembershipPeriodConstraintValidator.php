<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @Annotation
 */
class MembershipPeriodConstraintValidator extends ConstraintValidator
{
    /**
    * @var EntityManager
    */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($membershipPeriod, Constraint $constraint)
    {
        $membershipPeriods = $this->em->getRepository('AppBundle:MembershipPeriod')->findAll();
        foreach ($membershipPeriods as $mp) {

            //Don't compare to itself
            if ($mp == $membershipPeriod) {
                continue;
            }

            if ($mp->getSignupFromDate() <= $membershipPeriod->getSignupToDate() && $membershipPeriod->getSignupFromDate() <= $mp->getSignupToDate()) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('signupFromDate')
                    ->addViolation();
            }
        }
    }
}
