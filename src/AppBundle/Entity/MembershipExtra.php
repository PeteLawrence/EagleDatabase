<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class MembershipExtra
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(nullable=false)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(nullable=false)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MembershipTypePeriodExtra", mappedBy="membershipExtra")
     */
    private $membershipTypePeriodExtra;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->membershipTypePeriodExtra = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return MembershipExtra
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
     * Set description
     *
     * @param string $description
     *
     * @return MembershipExtra
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add membershipTypePeriodExtra
     *
     * @param \AppBundle\Entity\MembershipTypePeriodExtra $membershipTypePeriodExtra
     *
     * @return MembershipExtra
     */
    public function addMembershipTypePeriodExtra(\AppBundle\Entity\MembershipTypePeriodExtra $membershipTypePeriodExtra)
    {
        $this->membershipTypePeriodExtra[] = $membershipTypePeriodExtra;

        return $this;
    }

    /**
     * Remove membershipTypePeriodExtra
     *
     * @param \AppBundle\Entity\MembershipTypePeriodExtra $membershipTypePeriodExtra
     */
    public function removeMembershipTypePeriodExtra(\AppBundle\Entity\MembershipTypePeriodExtra $membershipTypePeriodExtra)
    {
        $this->membershipTypePeriodExtra->removeElement($membershipTypePeriodExtra);
    }

    /**
     * Get membershipTypePeriodExtra
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembershipTypePeriodExtra()
    {
        return $this->membershipTypePeriodExtra;
    }
}
