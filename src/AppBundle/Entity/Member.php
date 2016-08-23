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

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberRegistration", mappedBy="member")
     */
    private $memberRegistration;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->qualification = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set emergencyContactName
     *
     * @param string $emergencyContactName
     *
     * @return Member
     */
    public function setEmergencyContactName($emergencyContactName)
    {
        $this->emergencyContactName = $emergencyContactName;

        return $this;
    }

    /**
     * Get emergencyContactName
     *
     * @return string
     */
    public function getEmergencyContactName()
    {
        return $this->emergencyContactName;
    }

    /**
     * Set emergencyContactNumber
     *
     * @param string $emergencyContactNumber
     *
     * @return Member
     */
    public function setEmergencyContactNumber($emergencyContactNumber)
    {
        $this->emergencyContactNumber = $emergencyContactNumber;

        return $this;
    }

    /**
     * Get emergencyContactNumber
     *
     * @return string
     */
    public function getEmergencyContactNumber()
    {
        return $this->emergencyContactNumber;
    }

    /**
     * Set emergencyContactRelationship
     *
     * @param string $emergencyContactRelationship
     *
     * @return Member
     */
    public function setEmergencyContactRelationship($emergencyContactRelationship)
    {
        $this->emergencyContactRelationship = $emergencyContactRelationship;

        return $this;
    }

    /**
     * Get emergencyContactRelationship
     *
     * @return string
     */
    public function getEmergencyContactRelationship()
    {
        return $this->emergencyContactRelationship;
    }

    /**
     * Set bcuMembershipNumber
     *
     * @param integer $bcuMembershipNumber
     *
     * @return Member
     */
    public function setBcuMembershipNumber($bcuMembershipNumber)
    {
        $this->bcuMembershipNumber = $bcuMembershipNumber;

        return $this;
    }

    /**
     * Get bcuMembershipNumber
     *
     * @return integer
     */
    public function getBcuMembershipNumber()
    {
        return $this->bcuMembershipNumber;
    }

    /**
     * Add qualification
     *
     * @param \AppBundle\Entity\MemberQualification $qualification
     *
     * @return Member
     */
    public function addQualification(\AppBundle\Entity\MemberQualification $qualification)
    {
        $this->qualification[] = $qualification;

        return $this;
    }

    /**
     * Remove qualification
     *
     * @param \AppBundle\Entity\MemberQualification $qualification
     */
    public function removeQualification(\AppBundle\Entity\MemberQualification $qualification)
    {
        $this->qualification->removeElement($qualification);
    }

    /**
     * Get qualification
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQualification()
    {
        return $this->qualification;
    }

    /**
     * Add memberRegistration
     *
     * @param \AppBundle\Entity\MemberRegistration $memberRegistration
     *
     * @return Member
     */
    public function addMemberRegistration(\AppBundle\Entity\MemberRegistration $memberRegistration)
    {
        $this->memberRegistration[] = $memberRegistration;

        return $this;
    }

    /**
     * Remove memberRegistration
     *
     * @param \AppBundle\Entity\MemberRegistration $memberRegistration
     */
    public function removeMemberRegistration(\AppBundle\Entity\MemberRegistration $memberRegistration)
    {
        $this->memberRegistration->removeElement($memberRegistration);
    }

    /**
     * Get memberRegistration
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMemberRegistration()
    {
        return $this->memberRegistration;
    }
}
