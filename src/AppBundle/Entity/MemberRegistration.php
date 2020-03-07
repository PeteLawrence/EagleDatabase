<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MemberRegistration
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registrationDateTime;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\MemberRegistrationExtra",
     *     mappedBy="memberRegistration",
     *     cascade={"detach","persist","remove"}
     * )
     *
     */
    private $memberRegistrationExtra;

    /**
     * @ORM\OneToOne(
     *     targetEntity="AppBundle\Entity\MemberRegistrationCharge",
     *     mappedBy="memberRegistration",
     *     cascade={"detach","persist","remove"}
     * )
     */
    private $memberRegistrationCharge;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="memberRegistration")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MembershipTypePeriod", inversedBy="memberRegistration")
     * @ORM\JoinColumn(name="membership_type_period_id", referencedColumnName="id")
     */
    private $membershipTypePeriod;


    public function __construct()
    {
        $now = new \DateTime();
        $this->year = $now->format('Y');
        //$this->memberRegistrationExtra = [];
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
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return MemberRegistration
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
     * Set membershipTypePeriod
     *
     * @param \AppBundle\Entity\MembershipTypePeriod $membershipTypePeriod
     *
     * @return MemberRegistration
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
     * Set registrationDateTime
     *
     * @param \DateTime $registrationDateTime
     *
     * @return MemberRegistration
     */
    public function setRegistrationDateTime($registrationDateTime)
    {
        $this->registrationDateTime = $registrationDateTime;

        return $this;
    }

    /**
     * Get registrationDateTime
     *
     * @return \DateTime
     */
    public function getRegistrationDateTime()
    {
        return $this->registrationDateTime;
    }

    /**
     * Add memberRegistrationCharge
     *
     * @param \AppBundle\Entity\MemberRegistrationCharge $memberRegistrationCharge
     *
     * @return MemberRegistration
     */
    public function addMemberRegistrationCharge(\AppBundle\Entity\MemberRegistrationCharge $memberRegistrationCharge)
    {
        $this->memberRegistrationCharge[] = $memberRegistrationCharge;

        return $this;
    }

    /**
     * Remove memberRegistrationCharge
     *
     * @param \AppBundle\Entity\MemberRegistrationCharge $memberRegistrationCharge
     */
    public function removeMemberRegistrationCharge(\AppBundle\Entity\MemberRegistrationCharge $memberRegistrationCharge)
    {
        $this->memberRegistrationCharge->removeElement($memberRegistrationCharge);
    }

    /**
     * Get memberRegistrationCharge
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMemberRegistrationCharge()
    {
        return $this->memberRegistrationCharge;
    }

    /**
     * Add memberRegistrationExtra
     *
     * @param \AppBundle\Entity\MemberRegistrationExtra $memberRegistrationExtra
     *
     * @return MemberRegistration
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


    public function getTotal()
    {
        $total = $this->membershipTypePeriod->getPrice();

        if (sizeof($this->memberRegistrationExtra) > 0) {
            foreach ($this->memberRegistrationExtra as $extra) {
                $total += $extra->getMembershipTypePeriodExtra()->getValue();
            }
        }

        //Ensure that total doesn't become negative
        if ($total < 0) {
            $total = 0;
        }

        return $total;
    }

    /**
     * Set memberRegistrationCharge
     *
     * @param \AppBundle\Entity\MemberRegistrationCharge $memberRegistrationCharge
     *
     * @return MemberRegistration
     */
    public function setMemberRegistrationCharge(\AppBundle\Entity\MemberRegistrationCharge $memberRegistrationCharge = null)
    {
        $this->memberRegistrationCharge = $memberRegistrationCharge;

        return $this;
    }
}
