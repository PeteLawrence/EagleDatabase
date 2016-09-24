<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MemberRegistration
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registrationDateTime;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="memberRegistration")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MembershipTypePeriod", inversedBy="memberRegistration")
     * @ORM\JoinColumn(name="membership_type_period_id", referencedColumnName="id")
     */
    private $membershipTypePeriod;


    public function __construct()
    {
        $now = new \DateTime();
        $this->year = $now->format('Y');
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return MemberRegistration
     */
    public function setPerson(\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set membershipTypePeriod
     *
     * @param \AppBundle\Entity\MembershipTypePeriod $membershipTypePeriod
     *
     * @return MemberRegistration
     */
    public function setMembershipTypePeriod(\AppBundle\Entity\MembershipTypePeriod $membershipTypePeriod = null)
    {
        $this->membershipTypePeriod = $membershipTypePeriod;

        return $this;
    }

    /**
     * Get membershipTypePeriod
     *
     * @return \AppBundle\Entity\MembershipTypePeriod
     */
    public function getMembershipTypePeriod()
    {
        return $this->membershipTypePeriod;
    }

    /**
     * Set registrationDateTime
     *
     * @param \DateTime $registrationDateTime
     *
     * @return MemberRegistration
     */
    public function setRegistrationDateTime($registrationDateTime)
    {
        $this->registrationDateTime = $registrationDateTime;

        return $this;
    }

    /**
     * Get registrationDateTime
     *
     * @return \DateTime
     */
    public function getRegistrationDateTime()
    {
        return $this->registrationDateTime;
    }
}
