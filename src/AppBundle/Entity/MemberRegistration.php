<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $member_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MembershipType", inversedBy="memberRegistration")
     * @ORM\JoinColumn(name="membership_type_id", referencedColumnName="id")
     */
    private $membershipType;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="memberRegistration")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * 
     * 
     */
    private $member;

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
     * Set year
     *
     * @param integer $year
     *
     * @return MemberRegistration
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set membershipType
     *
     * @param \AppBundle\Entity\MembershipType $membershipType
     *
     * @return MemberRegistration
     */
    public function setMembershipType(\AppBundle\Entity\MembershipType $membershipType = null)
    {
        $this->membershipType = $membershipType;

        return $this;
    }

    /**
     * Get membershipType
     *
     * @return \AppBundle\Entity\MembershipType
     */
    public function getMembershipType()
    {
        return $this->membershipType;
    }

    /**
     * Set member
     *
     * @param \AppBundle\Entity\Member $member
     *
     * @return MemberRegistration
     */
    public function setMember(\AppBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \AppBundle\Entity\Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set memberId
     *
     * @param integer $memberId
     *
     * @return MemberRegistration
     */
    public function setMemberId($memberId)
    {
        $this->member_id = $memberId;

        return $this;
    }

    /**
     * Get memberId
     *
     * @return integer
     */
    public function getMemberId()
    {
        return $this->member_id;
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
}
