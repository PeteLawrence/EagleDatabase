<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 *
 */
class Person implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $forename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passwordResetToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passwordResetTokenExpiry;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $admin;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dob;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addr1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addr2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $town;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $county;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $disability;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Activity", mappedBy="organiser")
     */
    private $activity2;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Participant", mappedBy="person")
     */
    private $participant;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberQualification", mappedBy="person")
     */
    private $memberQualification;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberRegistration", mappedBy="person")
     */
    private $memberRegistration;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PersonAttribute", mappedBy="person")
     */
    private $personAttribute;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->activity2 = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set forename
     *
     * @param string $forename
     *
     * @return Person
     */
    public function setForename($forename)
    {
        $this->forename = $forename;

        return $this;
    }

    /**
     * Get forename
     *
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Person
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Person
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Person
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     *
     * @return Person
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Person
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     *
     * @return Person
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set addr1
     *
     * @param string $addr1
     *
     * @return Person
     */
    public function setAddr1($addr1)
    {
        $this->addr1 = $addr1;

        return $this;
    }

    /**
     * Get addr1
     *
     * @return string
     */
    public function getAddr1()
    {
        return $this->addr1;
    }

    /**
     * Set addr2
     *
     * @param string $addr2
     *
     * @return Person
     */
    public function setAddr2($addr2)
    {
        $this->addr2 = $addr2;

        return $this;
    }

    /**
     * Get addr2
     *
     * @return string
     */
    public function getAddr2()
    {
        return $this->addr2;
    }

    /**
     * Set town
     *
     * @param string $town
     *
     * @return Person
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set county
     *
     * @param string $county
     *
     * @return Person
     */
    public function setCounty($county)
    {
        $this->county = $county;

        return $this;
    }

    /**
     * Get county
     *
     * @return string
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     *
     * @return Person
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
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Person
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Person
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set disability
     *
     * @param boolean $disability
     *
     * @return Person
     */
    public function setDisability($disability)
    {
        $this->disability = $disability;

        return $this;
    }

    /**
     * Get disability
     *
     * @return boolean
     */
    public function getDisability()
    {
        return $this->disability;
    }

    /**
     * Add activity2
     *
     * @param \AppBundle\Entity\Activity $activity2
     *
     * @return Person
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

    /**
     * Add participant
     *
     * @param \AppBundle\Entity\Participant $participant
     *
     * @return Person
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
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }


    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        if ($this->admin) {
            return array('ROLE_ADMIN', 'ROLE_USER');
        } else {
            return array('ROLE_USER');
        }
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Add memberQualification
     *
     * @param \AppBundle\Entity\MemberQualification $memberQualification
     *
     * @return Person
     */
    public function addMemberQualification(\AppBundle\Entity\MemberQualification $memberQualification)
    {
        $this->memberQualification[] = $memberQualification;

        return $this;
    }

    /**
     * Remove memberQualification
     *
     * @param \AppBundle\Entity\MemberQualification $memberQualification
     */
    public function removeMemberQualification(\AppBundle\Entity\MemberQualification $memberQualification)
    {
        $this->memberQualification->removeElement($memberQualification);
    }

    /**
     * Get memberQualification
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMemberQualification()
    {
        return $this->memberQualification;
    }

    /**
     * Add memberRegistration
     *
     * @param \AppBundle\Entity\MemberRegistration $memberRegistration
     *
     * @return Person
     */
    public function addMemberRegistration(\AppBundle\Entity\MemberRegistration $memberRegistration)
    {
        $this->memberRegistration[] = $memberRegistration;

        return $this;
    }

    /**
     * Remove memberRegistration
     *
     * @param \AppBundle\Entity\MemberRegistration $memberRegistration
     */
    public function removeMemberRegistration(\AppBundle\Entity\MemberRegistration $memberRegistration)
    {
        $this->memberRegistration->removeElement($memberRegistration);
    }

    public function getCurrentMemberRegistration()
    {
        $now = new \DateTime();

        foreach ($this->memberRegistration as $memberRegistration) {
            if ($memberRegistration->getMembershipTypePeriod()->getMembershipPeriod()->getFromDate() < $now && $memberRegistration->getMembershipTypePeriod()->getMembershipPeriod()->getToDate() > $now) {
                return $memberRegistration;
            }
        }
    }

    /**
     * Get memberRegistration
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMemberRegistration()
    {
        return $this->memberRegistration;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return Person
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add personAttribute
     *
     * @param \AppBundle\Entity\PersonAttribute $personAttribute
     *
     * @return Person
     */
    public function addPersonAttribute(\AppBundle\Entity\PersonAttribute $personAttribute)
    {
        $this->personAttribute[] = $personAttribute;

        return $this;
    }

    /**
     * Remove personAttribute
     *
     * @param \AppBundle\Entity\PersonAttribute $personAttribute
     */
    public function removePersonAttribute(\AppBundle\Entity\PersonAttribute $personAttribute)
    {
        $this->personAttribute->removeElement($personAttribute);
    }

    /**
     * Get personAttribute
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonAttribute()
    {
        return $this->personAttribute;
    }



    public function __get($name)
    {
        if (is_array($this->personAttribute)) {
            dump('a');
            foreach ($this->personAttribute as $personAttribute) {
                if ($personAttribute->getAttribute()->getCode() == $name) {
                    return $personAttribute->getAttributeValue()->getValue();
                }
            }
        }

        return 'DEFAULT';
    }


    /**
     * Set passwordResetToken
     *
     * @param string $passwordResetToken
     *
     * @return Person
     */
    public function setPasswordResetToken($passwordResetToken)
    {
        $this->passwordResetToken = $passwordResetToken;

        return $this;
    }

    /**
     * Get passwordResetToken
     *
     * @return string
     */
    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }

    /**
     * Set passwordResetTokenExpiry
     *
     * @param \DateTime $passwordResetTokenExpiry
     *
     * @return Person
     */
    public function setPasswordResetTokenExpiry($passwordResetTokenExpiry)
    {
        $this->passwordResetTokenExpiry = $passwordResetTokenExpiry;

        return $this;
    }

    /**
     * Get passwordResetTokenExpiry
     *
     * @return \DateTime
     */
    public function getPasswordResetTokenExpiry()
    {
        return $this->passwordResetTokenExpiry;
    }
}
