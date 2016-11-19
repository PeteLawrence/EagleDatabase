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
}