<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

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
}
