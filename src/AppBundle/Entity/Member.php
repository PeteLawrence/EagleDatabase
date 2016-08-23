<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Member extends \AppBundle\Entity\Person
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emergencyContactName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emergencyContactNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emergencyContactRelationship;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bcuMembershipNumber;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberQualification", mappedBy="member")
     */
    private $qualification;
}