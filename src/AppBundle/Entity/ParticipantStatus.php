<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class ParticipantStatus
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
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $countsTowardsSize;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Participant", mappedBy="participantStatus")
     */
    private $participant;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManagedActivity", mappedBy="defaultParticipantStatus")
     */
    private $managedActivity;
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
     * Set status
     *
     * @param string $status
     *
     * @return ParticipantStatus
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add participant
     *
     * @param \AppBundle\Entity\Participant $participant
     *
     * @return ParticipantStatus
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
     * Set countsTowardsSize
     *
     * @param boolean $countsTowardsSize
     *
     * @return ParticipantStatus
     */
    public function setCountsTowardsSize($countsTowardsSize)
    {
        $this->countsTowardsSize = $countsTowardsSize;

        return $this;
    }

    /**
     * Get countsTowardsSize
     *
     * @return boolean
     */
    public function getCountsTowardsSize()
    {
        return $this->countsTowardsSize;
    }

    /**
     * Add managedActivity
     *
     * @param \AppBundle\Entity\ManagedActivity $managedActivity
     *
     * @return ParticipantStatus
     */
    public function addManagedActivity(\AppBundle\Entity\ManagedActivity $managedActivity)
    {
        $this->managedActivity[] = $managedActivity;

        return $this;
    }

    /**
     * Remove managedActivity
     *
     * @param \AppBundle\Entity\ManagedActivity $managedActivity
     */
    public function removeManagedActivity(\AppBundle\Entity\ManagedActivity $managedActivity)
    {
        $this->managedActivity->removeElement($managedActivity);
    }

    /**
     * Get managedActivity
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManagedActivity()
    {
        return $this->managedActivity;
    }
}
