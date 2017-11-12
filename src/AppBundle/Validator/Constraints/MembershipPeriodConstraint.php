<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MembershipPeriodConstraint extends Constraint
{
    public $message = 'Sign-up period overlaps with an existing MembershipPeriod';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}
