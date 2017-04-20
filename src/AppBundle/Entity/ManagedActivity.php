<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ManagedActivityRepository")
 */
class ManagedActivity extends \AppBundle\Entity\Activity
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $signinKey;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $signupStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $signupEnd;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Participant", mappedBy="managedActivity")
     */
    private $participant;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManagedActivityMembershipType", mappedBy="managedActivity")
     */
    private $managedActivityMembershipType;

    /**
     * Set signinKey
     *
     * @param \DateTime $signinKey
     *
     * @return ManagedActivity
     */
    public function setSigninKey($signinKey)
    {
        $this->signinKey = $signinKey;

        return $this;
    }

    /**
     * Get signinKey
     *
     * @return \DateTime
     */
    public function getSigninKey()
    {
        return $this->signinKey;
    }

    /**
     * Set signupStart
     *
     * @param \DateTime $signupStart
     *
     * @return ManagedActivity
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
     * @return ManagedActivity
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
     * @return ManagedActivity
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


    public function getPeople()
    {
        return sizeof($this->participant);
    }

    public function acceptingSignups()
    {
        if ($this->signupStart == null || $this->signupEnd == null) {
            return true;
        }

        $now = new \DateTime();

        return (($this->signupStart < $now) && ($this->signupEnd > $now));
    }

    /**
     * Add managedActivityMembershipType
     *
     * @param \AppBundle\Entity\ManagedActivityMembershipType $managedActivityMembershipType
     *
     * @return ManagedActivity
     */
    public function addManagedActivityMembershipType(\AppBundle\Entity\ManagedActivityMembershipType $managedActivityMembershipType)
    {
        $this->managedActivityMembershipType[] = $managedActivityMembershipType;

        return $this;
    }

    /**
     * Remove managedActivityMembershipType
     *
     * @param \AppBundle\Entity\ManagedActivityMembershipType $managedActivityMembershipType
     */
    public function removeManagedActivityMembershipType(\AppBundle\Entity\ManagedActivityMembershipType $managedActivityMembershipType)
    {
        $this->managedActivityMembershipType->removeElement($managedActivityMembershipType);
    }

    /**
     * Get managedActivityMembershipType
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManagedActivityMembershipType()
    {
        return $this->managedActivityMembershipType;
    }



    public function isAttending($person)
    {
        foreach ($this->participant as $p) {
            if ($p->getPerson() == $person) {
                return true;
            }
        }

        return false;
    }
}
