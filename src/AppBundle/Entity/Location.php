<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Location
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
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $postcode;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=6)
     */
    private $longitude;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=6)
     */
    private $latitude;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Activity", mappedBy="startLocation")
     */
    private $activity1;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Activity", mappedBy="endLocation")
     */
    private $activity2;

    /**
     * 
     */
    private $activity;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->activity = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Location
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
     * Set postcode
     *
     * @param string $postcode
     *
     * @return Location
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Add activity
     *
     * @param \AppBundle\Entity\Activity $activity
     *
     * @return Location
     */
    public function addActivity(\AppBundle\Entity\Activity $activity)
    {
        $this->activity[] = $activity;

        return $this;
    }

    /**
     * Remove activity
     *
     * @param \AppBundle\Entity\Activity $activity
     */
    public function removeActivity(\AppBundle\Entity\Activity $activity)
    {
        $this->activity->removeElement($activity);
    }

    /**
     * Get activity
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Location
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Location
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Location
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Add activity1
     *
     * @param \AppBundle\Entity\Activity $activity1
     *
     * @return Location
     */
    public function addActivity1(\AppBundle\Entity\Activity $activity1)
    {
        $this->activity1[] = $activity1;

        return $this;
    }

    /**
     * Remove activity1
     *
     * @param \AppBundle\Entity\Activity $activity1
     */
    public function removeActivity1(\AppBundle\Entity\Activity $activity1)
    {
        $this->activity1->removeElement($activity1);
    }

    /**
     * Get activity1
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivity1()
    {
        return $this->activity1;
    }

    /**
     * Add activity2
     *
     * @param \AppBundle\Entity\Activity $activity2
     *
     * @return Location
     */
    public function addActivity2(\AppBundle\Entity\Activity $activity2)
    {
        $this->activity2[] = $activity2;

        return $this;
    }

    /**
     * Remove activity2
     *
     * @param \AppBundle\Entity\Activity $activity2
     */
    public function removeActivity2(\AppBundle\Entity\Activity $activity2)
    {
        $this->activity2->removeElement($activity2);
    }

    /**
     * Get activity2
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivity2()
    {
        return $this->activity2;
    }
}
