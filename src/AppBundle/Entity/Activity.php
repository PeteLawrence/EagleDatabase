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
}