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
}
