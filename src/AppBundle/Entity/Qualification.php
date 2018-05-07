<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Qualification
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $verificationRequired;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $expiryDateRequired;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberQualification", mappedBy="qualification")
     */
    private $memberQualification;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->memberQualification = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Qualification
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add memberQualification
     *
     * @param \AppBundle\Entity\MemberQualification $memberQualification
     *
     * @return Qualification
     */
    public function addMemberQualification(\AppBundle\Entity\MemberQualification $memberQualification)
    {
        $this->memberQualification[] = $memberQualification;

        return $this;
    }

    /**
     * Remove memberQualification
     *
     * @param \AppBundle\Entity\MemberQualification $memberQualification
     */
    public function removeMemberQualification(\AppBundle\Entity\MemberQualification $memberQualification)
    {
        $this->memberQualification->removeElement($memberQualification);
    }

    /**
     * Get memberQualification
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMemberQualification()
    {
        return $this->memberQualification;
    }


    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set verificationRequired
     *
     * @param boolean $verificationRequired
     *
     * @return Qualification
     */
    public function setVerificationRequired($verificationRequired)
    {
        $this->verificationRequired = $verificationRequired;

        return $this;
    }

    /**
     * Get verificationRequired
     *
     * @return boolean
     */
    public function getVerificationRequired()
    {
        return $this->verificationRequired;
    }

    /**
     * Set expiryDateRequired
     *
     * @param boolean $expiryDateRequired
     *
     * @return Qualification
     */
    public function setExpiryDateRequired($expiryDateRequired)
    {
        $this->expiryDateRequired = $expiryDateRequired;

        return $this;
    }

    /**
     * Get expiryDateRequired
     *
     * @return boolean
     */
    public function getExpiryDateRequired()
    {
        return $this->expiryDateRequired;
    }
}
