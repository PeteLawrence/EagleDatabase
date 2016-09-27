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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $allowOnlineSignup;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WeatherDataPoint", mappedBy="activity")
     */
    private $weatherDataPoints;

    /**
     * 
     */
    private $weatherObservation;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Location", inversedBy="activity1")
     * @ORM\JoinColumn(name="start_location_id", referencedColumnName="id")
     */
    private $startLocation;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Location", inversedBy="activity2")
     * @ORM\JoinColumn(name="end_location_id", referencedColumnName="id")
     */
    private $endLocation;


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



    public function getNumberOfPeople()
    {
        $people = 0;
        foreach ($this->getParticipant() as $participant) {
            if ($participant->getParticipantStatus()->getCountsTowardsSize()) {
                $people++;
            }
        }

        return $people;
    }

    /**
     * Set allowOnlineSignup
     *
     * @param boolean $allowOnlineSignup
     *
     * @return Activity
     */
    public function setAllowOnlineSignup($allowOnlineSignup)
    {
        $this->allowOnlineSignup = $allowOnlineSignup;

        return $this;
    }

    /**
     * Get allowOnlineSignup
     *
     * @return boolean
     */
    public function getAllowOnlineSignup()
    {
        return $this->allowOnlineSignup;
    }

    /**
     * Set startLocation
     *
     * @param \AppBundle\Entity\Location $startLocation
     *
     * @return Activity
     */
    public function setStartLocation(\AppBundle\Entity\Location $startLocation = null)
    {
        $this->startLocation = $startLocation;

        return $this;
    }

    /**
     * Get startLocation
     *
     * @return \AppBundle\Entity\Location
     */
    public function getStartLocation()
    {
        return $this->startLocation;
    }

    /**
     * Set endLocation
     *
     * @param \AppBundle\Entity\Location $endLocation
     *
     * @return Activity
     */
    public function setEndLocation(\AppBundle\Entity\Location $endLocation = null)
    {
        $this->endLocation = $endLocation;

        return $this;
    }

    /**
     * Get endLocation
     *
     * @return \AppBundle\Entity\Location
     */
    public function getEndLocation()
    {
        return $this->endLocation;
    }

    /**
     * Add weatherObservation
     *
     * @param \AppBundle\Entity\WeatherDataPoint $weatherObservation
     *
     * @return Activity
     */
    public function addWeatherObservation(\AppBundle\Entity\WeatherDataPoint $weatherObservation)
    {
        $this->weatherObservation[] = $weatherObservation;

        return $this;
    }

    /**
     * Remove weatherObservation
     *
     * @param \AppBundle\Entity\WeatherDataPoint $weatherObservation
     */
    public function removeWeatherObservation(\AppBundle\Entity\WeatherDataPoint $weatherObservation)
    {
        $this->weatherObservation->removeElement($weatherObservation);
    }

    /**
     * Get weatherObservation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWeatherObservation()
    {
        return $this->weatherObservation;
    }

    /**
     * Add weatherDataPoint
     *
     * @param \AppBundle\Entity\WeatherDataPoint $weatherDataPoint
     *
     * @return Activity
     */
    public function addWeatherDataPoint(\AppBundle\Entity\WeatherDataPoint $weatherDataPoint)
    {
        $this->weatherDataPoints[] = $weatherDataPoint;

        return $this;
    }

    /**
     * Remove weatherDataPoint
     *
     * @param \AppBundle\Entity\WeatherDataPoint $weatherDataPoint
     */
    public function removeWeatherDataPoint(\AppBundle\Entity\WeatherDataPoint $weatherDataPoint)
    {
        $this->weatherDataPoints->removeElement($weatherDataPoint);
    }

    /**
     * Get weatherDataPoints
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWeatherDataPoints()
    {
        return $this->weatherDataPoints;
    }
}
