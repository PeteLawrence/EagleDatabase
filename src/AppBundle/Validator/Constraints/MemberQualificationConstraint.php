<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MemberQualificationConstraint extends Constraint
{
    public $message = 'Expiry date is required for this type of qualification';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}
