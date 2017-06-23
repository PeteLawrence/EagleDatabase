<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MemberQualification
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $validFrom;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $validTo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $attachment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $verifiedDateTime;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Qualification", inversedBy="memberQualification")
     * @ORM\JoinColumn(name="qualification_id", referencedColumnName="id")
     */
    private $qualification;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="memberQualification")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="verifiedMemberQualifications")
     * @ORM\JoinColumn(name="verifiedBy", referencedColumnName="id")
     */
    private $verifiedBy;

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
     * Set qualification
     *
     * @param \AppBundle\Entity\Qualification $qualification
     *
     * @return MemberQualification
     */
    public function setQualification(\AppBundle\Entity\Qualification $qualification = null)
    {
        $this->qualification = $qualification;

        return $this;
    }

    /**
     * Get qualification
     *
     * @return \AppBundle\Entity\Qualification
     */
    public function getQualification()
    {
        return $this->qualification;
    }

    /**
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return MemberQualification
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
     * Set validFrom
     *
     * @param \DateTime $validFrom
     *
     * @return MemberQualification
     */
    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    /**
     * Get validFrom
     *
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * Set validTo
     *
     * @param \DateTime $validTo
     *
     * @return MemberQualification
     */
    public function setValidTo($validTo)
    {
        $this->validTo = $validTo;

        return $this;
    }

    /**
     * Get validTo
     *
     * @return \DateTime
     */
    public function getValidTo()
    {
        return $this->validTo;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return MemberQualification
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return MemberQualification
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }


    public function isExpired()
    {
        if (!$this->validTo) {
            //Qualification does not expire
            return false;
        }

        $now = new \DateTime();

        return ($this->validTo < $now);
    }


    public function isExpiringSoon()
    {
        if (!$this->validTo) {
            //Qualification does not expire
            return false;
        }
        
        $now = new \DateTime();

        $limit = $this->validTo->sub(new \DateInterval('P3M'));

        return ($limit < $now);
    }

    /**
     * Set attachment
     *
     * @param string $attachment
     *
     * @return MemberQualification
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * Get attachment
     *
     * @return string
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set verifiedDateTime
     *
     * @param \DateTime $verifiedDateTime
     *
     * @return MemberQualification
     */
    public function setVerifiedDateTime($verifiedDateTime)
    {
        $this->verifiedDateTime = $verifiedDateTime;

        return $this;
    }

    /**
     * Get verifiedDateTime
     *
     * @return \DateTime
     */
    public function getVerifiedDateTime()
    {
        return $this->verifiedDateTime;
    }

    /**
     * Set verifiedBy
     *
     * @param \AppBundle\Entity\Person $verifiedBy
     *
     * @return MemberQualification
     */
    public function setVerifiedBy(\AppBundle\Entity\Person $verifiedBy = null)
    {
        $this->verifiedBy = $verifiedBy;

        return $this;
    }

    /**
     * Get verifiedBy
     *
     * @return \AppBundle\Entity\Person
     */
    public function getVerifiedBy()
    {
        return $this->verifiedBy;
    }
}
