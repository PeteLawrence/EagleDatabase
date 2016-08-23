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
}