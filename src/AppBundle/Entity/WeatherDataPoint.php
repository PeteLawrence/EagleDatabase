<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class WeatherDataPoint
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
    private $summary;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $precipitationIntensity;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $precipitationProbability;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $precipitationType;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $temperature;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $windSpeed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $windBearing;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $visibility;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $cloudCover;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $humidity;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $pressure;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $time;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $timeZone;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Activity", inversedBy="weatherDataPoints")
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id")
     */
    private $activity;

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
     * Set summary
     *
     * @param string $summary
     *
     * @return WeatherDataPoint
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return WeatherDataPoint
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set precipitationIntensity
     *
     * @param string $precipitationIntensity
     *
     * @return WeatherDataPoint
     */
    public function setPrecipitationIntensity($precipitationIntensity)
    {
        $this->precipitationIntensity = $precipitationIntensity;

        return $this;
    }

    /**
     * Get precipitationIntensity
     *
     * @return string
     */
    public function getPrecipitationIntensity()
    {
        return $this->precipitationIntensity;
    }

    /**
     * Set precipitationProbability
     *
     * @param string $precipitationProbability
     *
     * @return WeatherDataPoint
     */
    public function setPrecipitationProbability($precipitationProbability)
    {
        $this->precipitationProbability = $precipitationProbability;

        return $this;
    }

    /**
     * Get precipitationProbability
     *
     * @return string
     */
    public function getPrecipitationProbability()
    {
        return $this->precipitationProbability;
    }

    /**
     * Set temperature
     *
     * @param string $temperature
     *
     * @return WeatherDataPoint
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * Get temperature
     *
     * @return string
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * Set windSpeed
     *
     * @param string $windSpeed
     *
     * @return WeatherDataPoint
     */
    public function setWindSpeed($windSpeed)
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    /**
     * Get windSpeed
     *
     * @return string
     */
    public function getWindSpeed()
    {
        return $this->windSpeed;
    }

    /**
     * Set windBearing
     *
     * @param integer $windBearing
     *
     * @return WeatherDataPoint
     */
    public function setWindBearing($windBearing)
    {
        $this->windBearing = $windBearing;

        return $this;
    }

    /**
     * Get windBearing
     *
     * @return integer
     */
    public function getWindBearing()
    {
        return $this->windBearing;
    }

    /**
     * Set visibility
     *
     * @param string $visibility
     *
     * @return WeatherDataPoint
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set cloudCover
     *
     * @param string $cloudCover
     *
     * @return WeatherDataPoint
     */
    public function setCloudCover($cloudCover)
    {
        $this->cloudCover = $cloudCover;

        return $this;
    }

    /**
     * Get cloudCover
     *
     * @return string
     */
    public function getCloudCover()
    {
        return $this->cloudCover;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return WeatherDataPoint
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set activity
     *
     * @param \AppBundle\Entity\Activity $activity
     *
     * @return WeatherDataPoint
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

    /**
     * Set timeZone
     *
     * @param string $timeZone
     *
     * @return WeatherDataPoint
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * Get timeZone
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * Set precipitationType
     *
     * @param string $precipitationType
     *
     * @return WeatherDataPoint
     */
    public function setPrecipitationType($precipitationType)
    {
        $this->precipitationType = $precipitationType;

        return $this;
    }

    /**
     * Get precipitationType
     *
     * @return string
     */
    public function getPrecipitationType()
    {
        return $this->precipitationType;
    }

    /**
     * Set humidity
     *
     * @param string $humidity
     *
     * @return WeatherDataPoint
     */
    public function setHumidity($humidity)
    {
        $this->humidity = $humidity;

        return $this;
    }

    /**
     * Get humidity
     *
     * @return string
     */
    public function getHumidity()
    {
        return $this->humidity;
    }

    /**
     * Set pressure
     *
     * @param integer $pressure
     *
     * @return WeatherDataPoint
     */
    public function setPressure($pressure)
    {
        $this->pressure = $pressure;

        return $this;
    }

    /**
     * Get pressure
     *
     * @return integer
     */
    public function getPressure()
    {
        return $this->pressure;
    }
}
