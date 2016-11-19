<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ManagedActivityMembershipType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $countsTowardsSize;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ManagedActivity", inversedBy="managedActivityMembershipType")
     * @ORM\JoinColumn(name="managed_activity_id", referencedColumnName="id")
     */
    private $managedActivity;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MembershipType", inversedBy="managedActivityMembershipType")
     * @ORM\JoinColumn(name="membership_type_id", referencedColumnName="id")
     */
    private $membershipType;

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
     * Set countsTowardsSize
     *
     * @param boolean $countsTowardsSize
     *
     * @return ManagedActivityMembershipType
     */
    public function setCountsTowardsSize($countsTowardsSize)
    {
        $this->countsTowardsSize = $countsTowardsSize;

        return $this;
    }

    /**
     * Get countsTowardsSize
     *
     * @return boolean
     */
    public function getCountsTowardsSize()
    {
        return $this->countsTowardsSize;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return ManagedActivityMembershipType
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
     * Set managedActivity
     *
     * @param \AppBundle\Entity\ManagedActivity $managedActivity
     *
     * @return ManagedActivityMembershipType
     */
    public function setManagedActivity(\AppBundle\Entity\ManagedActivity $managedActivity = null)
    {
        $this->managedActivity = $managedActivity;

        return $this;
    }

    /**
     * Get managedActivity
     *
     * @return \AppBundle\Entity\ManagedActivity
     */
    public function getManagedActivity()
    {
        return $this->managedActivity;
    }

    /**
     * Set membershipType
     *
     * @param \AppBundle\Entity\MembershipType $membershipType
     *
     * @return ManagedActivityMembershipType
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
}
