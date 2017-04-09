<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MemberRegistrationExtra
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MembershipTypePeriodExtra", inversedBy="memberRegistrationExtra")
     * @ORM\JoinColumn(name="membership_type_period_extra_id", referencedColumnName="id")
     */
    private $membershipTypePeriodExtra;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MemberRegistration", inversedBy="memberRegistrationExtra")
     * @ORM\JoinColumn(name="member_registration_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $memberRegistration;

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
     * Set membershipTypePeriodExtra
     *
     * @param \AppBundle\Entity\MembershipTypePeriodExtra $membershipTypePeriodExtra
     *
     * @return MemberRegistrationExtra
     */
    public function setMembershipTypePeriodExtra(\AppBundle\Entity\MembershipTypePeriodExtra $membershipTypePeriodExtra = null)
    {
        $this->membershipTypePeriodExtra = $membershipTypePeriodExtra;

        return $this;
    }

    /**
     * Get membershipTypePeriodExtra
     *
     * @return \AppBundle\Entity\MembershipTypePeriodExtra
     */
    public function getMembershipTypePeriodExtra()
    {
        return $this->membershipTypePeriodExtra;
    }

    /**
     * Set memberRegistration
     *
     * @param \AppBundle\Entity\MemberRegistration $memberRegistration
     *
     * @return MemberRegistrationExtra
     */
    public function setMemberRegistration(\AppBundle\Entity\MemberRegistration $memberRegistration = null)
    {
        $this->memberRegistration = $memberRegistration;

        return $this;
    }

    /**
     * Get memberRegistration
     *
     * @return \AppBundle\Entity\MemberRegistration
     */
    public function getMemberRegistration()
    {
        return $this->memberRegistration;
    }
}
