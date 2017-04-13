<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MembershipType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $adminSelectableOnly;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MembershipTypePeriod", mappedBy="membershipType")
     */
    private $membershipTypePeriod;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManagedActivityMembershipType", mappedBy="membershipType")
     */
    private $managedActivityMembershipType;

    /**
     *
     */
    private $memberRegistration;
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
     * Set type
     *
     * @param string $type
     *
     * @return MembershipType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add memberRegistration
     *
     * @param \AppBundle\Entity\MemberRegistration $memberRegistration
     *
     * @return MembershipType
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
     * Add membershipTypePeriod
     *
     * @param \AppBundle\Entity\MembershipTypePeriod $membershipTypePeriod
     *
     * @return MembershipType
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
     * Add managedActivityMembershipType
     *
     * @param \AppBundle\Entity\ManagedActivityMembershipType $managedActivityMembershipType
     *
     * @return MembershipType
     */
    public function addManagedActivityMembershipType(\AppBundle\Entity\ManagedActivityMembershipType $managedActivityMembershipType)
    {
        $this->managedActivityMembershipType[] = $managedActivityMembershipType;

        return $this;
    }

    /**
     * Remove managedActivityMembershipType
     *
     * @param \AppBundle\Entity\ManagedActivityMembershipType $managedActivityMembershipType
     */
    public function removeManagedActivityMembershipType(\AppBundle\Entity\ManagedActivityMembershipType $managedActivityMembershipType)
    {
        $this->managedActivityMembershipType->removeElement($managedActivityMembershipType);
    }

    /**
     * Get managedActivityMembershipType
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManagedActivityMembershipType()
    {
        return $this->managedActivityMembershipType;
    }

    /**
     * Set adminSelectableOnly
     *
     * @param boolean $adminSelectableOnly
     *
     * @return MembershipType
     */
    public function setAdminSelectableOnly($adminSelectableOnly)
    {
        $this->adminSelectableOnly = $adminSelectableOnly;

        return $this;
    }

    /**
     * Get adminSelectableOnly
     *
     * @return boolean
     */
    public function getAdminSelectableOnly()
    {
        return $this->adminSelectableOnly;
    }
}
