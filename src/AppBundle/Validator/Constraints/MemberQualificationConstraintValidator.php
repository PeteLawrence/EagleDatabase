<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class MemberQualificationConstraintValidator extends ConstraintValidator
{
    public function validate($memberQualification, Constraint $constraint)
    {
        if ($memberQualification->getQualification()->getExpiryDateRequired() && $memberQualification->getValidTo() == null) {
            $this->context->buildViolation($constraint->message)
                ->atPath('validTo')
                ->addViolation();
        }
    }
}
