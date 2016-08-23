<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Activity
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
    private $activityStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activityEnd;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $spaces;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $signupStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $signupEnd;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Participant", mappedBy="activity")
     */
    private $participant;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ActivityType", inversedBy="activity")
     * @ORM\JoinColumn(name="activity_type_id", referencedColumnName="id")
     */
    private $activityType;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="activity2")
     * @ORM\JoinColumn(name="organiser_id", referencedColumnName="id")
     */
    private $organiser;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Location", inversedBy="activity")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->participant = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set activityStart
     *
     * @param \DateTime $activityStart
     *
     * @return Activity
     */
    public function setActivityStart($activityStart)
    {
        $this->activityStart = $activityStart;

        return $this;
    }

    /**
     * Get activityStart
     *
     * @return \DateTime
     */
    public function getActivityStart()
    {
        return $this->activityStart;
    }

    /**
     * Set activityEnd
     *
     * @param \DateTime $activityEnd
     *
     * @return Activity
     */
    public function setActivityEnd($activityEnd)
    {
        $this->activityEnd = $activityEnd;

        return $this;
    }

    /**
     * Get activityEnd
     *
     * @return \DateTime
     */
    public function getActivityEnd()
    {
        return $this->activityEnd;
    }

    /**
     * Set spaces
     *
     * @param integer $spaces
     *
     * @return Activity
     */
    public function setSpaces($spaces)
    {
        $this->spaces = $spaces;

        return $this;
    }

    /**
     * Get spaces
     *
     * @return integer
     */
    public function getSpaces()
    {
        return $this->spaces;
    }

    /**
     * Set signupStart
     *
     * @param \DateTime $signupStart
     *
     * @return Activity
     */
    public function setSignupStart($signupStart)
    {
        $this->signupStart = $signupStart;

        return $this;
    }

    /**
     * Get signupStart
     *
     * @return \DateTime
     */
    public function getSignupStart()
    {
        return $this->signupStart;
    }

    /**
     * Set signupEnd
     *
     * @param \DateTime $signupEnd
     *
     * @return Activity
     */
    public function setSignupEnd($signupEnd)
    {
        $this->signupEnd = $signupEnd;

        return $this;
    }

    /**
     * Get signupEnd
     *
     * @return \DateTime
     */
    public function getSignupEnd()
    {
        return $this->signupEnd;
    }

    /**
     * Add participant
     *
     * @param \AppBundle\Entity\Participant $participant
     *
     * @return Activity
     */
    public function addParticipant(\AppBundle\Entity\Participant $participant)
    {
        $this->participant[] = $participant;

        return $this;
    }

    /**
     * Remove participant
     *
     * @param \AppBundle\Entity\Participant $participant
     */
    public function removeParticipant(\AppBundle\Entity\Participant $participant)
    {
        $this->participant->removeElement($participant);
    }

    /**
     * Get participant
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Set activityType
     *
     * @param \AppBundle\Entity\ActivityType $activityType
     *
     * @return Activity
     */
    public function setActivityType(\AppBundle\Entity\ActivityType $activityType = null)
    {
        $this->activityType = $activityType;

        return $this;
    }

    /**
     * Get activityType
     *
     * @return \AppBundle\Entity\ActivityType
     */
    public function getActivityType()
    {
        return $this->activityType;
    }

    /**
     * Set organiser
     *
     * @param \AppBundle\Entity\Person $organiser
     *
     * @return Activity
     */
    public function setOrganiser(\AppBundle\Entity\Person $organiser = null)
    {
        $this->organiser = $organiser;

        return $this;
    }

    /**
     * Get organiser
     *
     * @return \AppBundle\Entity\Person
     */
    public function getOrganiser()
    {
        return $this->organiser;
    }

    /**
     * Set location
     *
     * @param \AppBundle\Entity\Location $location
     *
     * @return Activity
     */
    public function setLocation(\AppBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \AppBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }
}
