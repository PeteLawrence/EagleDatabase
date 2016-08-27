<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Activity", inversedBy="participant")
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id")
     */
    private $activity;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ParticipantStatus", inversedBy="participant")
     * @ORM\JoinColumn(name="participant_status_id", referencedColumnName="id")
     */
    private $participantStatus;

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
     * Set activity
     *
     * @param \AppBundle\Entity\Activity $activity
     *
     * @return Participant
     */
    public function setActivity(\AppBundle\Entity\Activity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \AppBundle\Entity\Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
