<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MembershipTypePeriodExtra
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", nullable=false)
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberRegistrationExtra", mappedBy="membershipTypePeriodExtra")
     */
    private $memberRegistrationExtra;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MembershipTypePeriod", inversedBy="membershipTypePeriodExtra")
     * @ORM\JoinColumn(name="membership_type_period_id", referencedColumnName="id")
     */
    private $membershipTypePeriod;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MembershipExtra", inversedBy="membershipTypePeriodExtra")
     * @ORM\JoinColumn(name="membership_extra_id", referencedColumnName="id")
     */
    private $membershipExtra;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->memberRegistrationExtra = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set value
     *
     * @param string $value
     *
     * @return MembershipTypePeriodExtra
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Add memberRegistrationExtra
     *
     * @param \AppBundle\Entity\MemberRegistrationExtra $memberRegistrationExtra
     *
     * @return MembershipTypePeriodExtra
     */
    public function addMemberRegistrationExtra(\AppBundle\Entity\MemberRegistrationExtra $memberRegistrationExtra)
    {
        $this->memberRegistrationExtra[] = $memberRegistrationExtra;

        return $this;
    }

    /**
     * Remove memberRegistrationExtra
     *
     * @param \AppBundle\Entity\MemberRegistrationExtra $memberRegistrationExtra
     */
    public function removeMemberRegistrationExtra(\AppBundle\Entity\MemberRegistrationExtra $memberRegistrationExtra)
    {
        $this->memberRegistrationExtra->removeElement($memberRegistrationExtra);
    }

    /**
     * Get memberRegistrationExtra
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMemberRegistrationExtra()
    {
        return $this->memberRegistrationExtra;
    }

    /**
     * Set membershipTypePeriod
     *
     * @param \AppBundle\Entity\MembershipTypePeriod $membershipTypePeriod
     *
     * @return MembershipTypePeriodExtra
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
     * Set membershipExtra
     *
     * @param \AppBundle\Entity\MembershipExtra $membershipExtra
     *
     * @return MembershipTypePeriodExtra
     */
    public function setMembershipExtra(\AppBundle\Entity\MembershipExtra $membershipExtra = null)
    {
        $this->membershipExtra = $membershipExtra;

        return $this;
    }

    /**
     * Get membershipExtra
     *
     * @return \AppBundle\Entity\MembershipExtra
     */
    public function getMembershipExtra()
    {
        return $this->membershipExtra;
    }
}
