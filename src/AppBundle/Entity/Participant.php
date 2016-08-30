<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Participant
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
    private $signupDatetime;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ParticipantRole", inversedBy="participant")
     * @ORM\JoinColumn(name="participant_role_id", referencedColumnName="id")
     */
    private $participantRole;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="participant")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ParticipantStatus", inversedBy="participant")
     * @ORM\JoinColumn(name="participant_status_id", referencedColumnName="id")
     */
    private $participantStatus;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ManagedActivity", inversedBy="participant")
     * @ORM\JoinColumn(name="managed_activity_id", referencedColumnName="id")
     */
    private $managedActivity;

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
     * Set signupDatetime
     *
     * @param \DateTime $signupDatetime
     *
     * @return Participant
     */
    public function setSignupDatetime($signupDatetime)
    {
        $this->signupDatetime = $signupDatetime;

        return $this;
    }

    /**
     * Get signupDatetime
     *
     * @return \DateTime
     */
    public function getSignupDatetime()
    {
        return $this->signupDatetime;
    }

    /**
     * Set participantRole
     *
     * @param \AppBundle\Entity\ParticipantRole $participantRole
     *
     * @return Participant
     */
    public function setParticipantRole(\AppBundle\Entity\ParticipantRole $participantRole = null)
    {
        $this->participantRole = $participantRole;

        return $this;
    }

    /**
     * Get participantRole
     *
     * @return \AppBundle\Entity\ParticipantRole
     */
    public function getParticipantRole()
    {
        return $this->participantRole;
    }

    /**
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return Participant
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
     * Set participantStatus
     *
     * @param \AppBundle\Entity\ParticipantStatus $participantStatus
     *
     * @return Participant
     */
    public function setParticipantStatus(\AppBundle\Entity\ParticipantStatus $participantStatus = null)
    {
        $this->participantStatus = $participantStatus;

        return $this;
    }

    /**
     * Get participantStatus
     *
     * @return \AppBundle\Entity\ParticipantStatus
     */
    public function getParticipantStatus()
    {
        return $this->participantStatus;
    }

    /**
     * Set managedActivity
     *
     * @param \AppBundle\Entity\ManagedActivity $managedActivity
     *
     * @return Participant
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
}
