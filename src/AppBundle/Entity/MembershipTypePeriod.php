<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MembershipTypePeriod
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     */
    private $fromDate;

    /**
     * 
     */
    private $toDate;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberRegistration", mappedBy="membershipTypePeriod")
     */
    private $memberRegistration;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MembershipType", inversedBy="membershipTypePeriod")
     * @ORM\JoinColumn(name="membership_type_id", referencedColumnName="id")
     */
    private $membershipType;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MembershipPeriod", inversedBy="membershipTypePeriod")
     * @ORM\JoinColumn(name="membership_period_id", referencedColumnName="id")
     */
    private $membershipPeriod;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->memberRegistration = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set price
     *
     * @param string $price
     *
     * @return MembershipTypePeriod
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Add memberRegistration
     *
     * @param \AppBundle\Entity\MemberRegistration $memberRegistration
     *
     * @return MembershipTypePeriod
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

    /**
     * Set membershipType
     *
     * @param \AppBundle\Entity\MembershipType $membershipType
     *
     * @return MembershipTypePeriod
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
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return MembershipTypePeriod
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return \DateTime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param \DateTime $toDate
     *
     * @return MembershipTypePeriod
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return \DateTime
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set membershipPeriod
     *
     * @param \AppBundle\Entity\MembershipPeriod $membershipPeriod
     *
     * @return MembershipTypePeriod
     */
    public function setMembershipPeriod(\AppBundle\Entity\MembershipPeriod $membershipPeriod = null)
    {
        $this->membershipPeriod = $membershipPeriod;

        return $this;
    }

    /**
     * Get membershipPeriod
     *
     * @return \AppBundle\Entity\MembershipPeriod
     */
    public function getMembershipPeriod()
    {
        return $this->membershipPeriod;
    }
}
