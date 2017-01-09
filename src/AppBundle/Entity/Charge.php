<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ChargeRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "activity":"AppBundle\Entity\ActivityCharge",
 *     "memberregistration":"AppBundle\Entity\MemberRegistrationCharge",
 *     "other":"AppBundle\Entity\OtherCharge"
 * })
 */
abstract class Charge
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", nullable=false, precision=8, scale=2)
     * @Assert\NotBlank
     */
    private $amount;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $paid;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $paiddatetime;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $duedatetime;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createddatetime;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="charge")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PaymentType", inversedBy="charge")
     * @ORM\JoinColumn(name="payment_type_id", referencedColumnName="id")
     */
    private $paymentType;

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
     * Set amount
     *
     * @param string $amount
     *
     * @return Charge
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Charge
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     *
     * @return Charge
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set paiddatetime
     *
     * @param \DateTime $paiddatetime
     *
     * @return Charge
     */
    public function setPaiddatetime($paiddatetime)
    {
        $this->paiddatetime = $paiddatetime;

        return $this;
    }

    /**
     * Get paiddatetime
     *
     * @return \DateTime
     */
    public function getPaiddatetime()
    {
        return $this->paiddatetime;
    }

    /**
     * Set duedatetime
     *
     * @param \DateTime $duedatetime
     *
     * @return Charge
     */
    public function setDuedatetime($duedatetime)
    {
        $this->duedatetime = $duedatetime;

        return $this;
    }

    /**
     * Get duedatetime
     *
     * @return \DateTime
     */
    public function getDuedatetime()
    {
        return $this->duedatetime;
    }

    /**
     * Set createddatetime
     *
     * @param \DateTime $createddatetime
     *
     * @return Charge
     */
    public function setCreateddatetime($createddatetime)
    {
        $this->createddatetime = $createddatetime;

        return $this;
    }

    /**
     * Get createddatetime
     *
     * @return \DateTime
     */
    public function getCreateddatetime()
    {
        return $this->createddatetime;
    }

    /**
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return Charge
     */
    public function setPerson(\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set paymentType
     *
     * @param \AppBundle\Entity\PaymentType $paymentType
     *
     * @return Charge
     */
    public function setPaymentType(\AppBundle\Entity\PaymentType $paymentType = null)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return \AppBundle\Entity\PaymentType
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Charge
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
}
