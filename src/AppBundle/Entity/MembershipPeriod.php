<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity
 */
class MembershipPeriod
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
    private $fromDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $toDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $signupFromDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $signupToDate;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MembershipTypePeriod", mappedBy="membershipPeriod")
     */
    private $membershipTypePeriod;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->membershipTypePeriod = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return MembershipPeriod
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
     * @return MembershipPeriod
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
     * Add membershipTypePeriod
     *
     * @param \AppBundle\Entity\MembershipTypePeriod $membershipTypePeriod
     *
     * @return MembershipPeriod
     */
    public function addMembershipTypePeriod(\AppBundle\Entity\MembershipTypePeriod $membershipTypePeriod)
    {
        $this->membershipTypePeriod[] = $membershipTypePeriod;

        return $this;
    }

    /**
     * Remove membershipTypePeriod
     *
     * @param \AppBundle\Entity\MembershipTypePeriod $membershipTypePeriod
     */
    public function removeMembershipTypePeriod(\AppBundle\Entity\MembershipTypePeriod $membershipTypePeriod)
    {
        $this->membershipTypePeriod->removeElement($membershipTypePeriod);
    }

    /**
     * Get membershipTypePeriod
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembershipTypePeriod()
    {
        return $this->membershipTypePeriod;
    }

    /**
     * Set signupFromDate
     *
     * @param \DateTime $signupFromDate
     *
     * @return MembershipPeriod
     */
    public function setSignupFromDate($signupFromDate)
    {
        $this->signupFromDate = $signupFromDate;

        return $this;
    }

    /**
     * Get signupFromDate
     *
     * @return \DateTime
     */
    public function getSignupFromDate()
    {
        return $this->signupFromDate;
    }

    /**
     * Set signupToDate
     *
     * @param \DateTime $signupToDate
     *
     * @return MembershipPeriod
     */
    public function setSignupToDate($signupToDate)
    {
        $this->signupToDate = $signupToDate;

        return $this;
    }

    /**
     * Get signupToDate
     *
     * @return \DateTime
     */
    public function getSignupToDate()
    {
        return $this->signupToDate;
    }



    public function getName()
    {
        return sprintf('%s → %s', $this->fromDate->format('jS M Y'), $this->toDate->format('jS M Y'));
    }
}
