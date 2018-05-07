<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class PaymentType
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
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Charge", mappedBy="paymentType")
     */
    private $charge;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->charge = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PaymentType
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
     * Add charge
     *
     * @param \AppBundle\Entity\Charge $charge
     *
     * @return PaymentType
     */
    public function addCharge(\AppBundle\Entity\Charge $charge)
    {
        $this->charge[] = $charge;

        return $this;
    }

    /**
     * Remove charge
     *
     * @param \AppBundle\Entity\Charge $charge
     */
    public function removeCharge(\AppBundle\Entity\Charge $charge)
    {
        $this->charge->removeElement($charge);
    }

    /**
     * Get charge
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCharge()
    {
        return $this->charge;
    }
}
