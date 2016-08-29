<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type2", type="string")
 * @ORM\DiscriminatorMap({"managed"="AppBundle\Entity\ManagedActivity","unmanaged"="AppBundle\Entity\UnmanagedActivity"})
 */
abstract class Activity
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
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Activity
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
     * @return Activity
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



    public function getNumberOfPeople($type = '')
    {
        $people = 0;
        foreach ($this->getParticipant() as $participant) {
            if ($participant->getParticipantStatus()->getCountsTowardsSize()) {
                $people++;
            }
        }

        return $people;
    }
}
