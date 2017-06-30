<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Ambta\DoctrineEncryptBundle\Configuration\Encrypted;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PersonRepository")
 *
 */
class Person implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Encrypted
     */
    private $forename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Encrypted
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Encrypted
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
     * @Encrypted
     */
    private $gender;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dob;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Encrypted
     */
    private $addr1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Encrypted
     */
    private $addr2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Encrypted
     */
    private $addr3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Encrypted
     */
    private $town;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Encrypted
     */
    private $county;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Encrypted
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Encrypted
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Encrypted
     */
    private $mobile;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $disability;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Encrypted
     */
    private $notes;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Encrypted
     */
    private $nextOfKinName;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Encrypted
     */
    private $nextOfKinRelation;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Encrypted
     */
    private $nextOfKinContactDetails;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     * @Encrypted
     */
    private $bcMembershipNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoginDateTime;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Activity", mappedBy="organiser")
     */
    private $activity2;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ActivityMessage", mappedBy="person")
     */
    private $activityMessage;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Charge", mappedBy="person")
     */
    private $charge;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Participant", mappedBy="person")
     */
    private $participant;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberQualification", mappedBy="person")
     */
    private $memberQualification;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberQualification", mappedBy="verifiedBy")
     */
    private $verifiedMemberQualifications;

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
        return $this->id;
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

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
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
            $this->isActive
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
            $this->isActive
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

    public function getMemberRegistrationAtDate($date)
    {
        foreach ($this->memberRegistration as $memberRegistration) {
            if ($memberRegistration->getMembershipTypePeriod()->getMembershipPeriod()->getFromDate() < $date && $memberRegistration->getMembershipTypePeriod()->getMembershipPeriod()->getToDate() > $date) {
                return $memberRegistration;
            }
        }
    }

    public function getCurrentMemberRegistration()
    {
        $now = new \DateTime();

        return $this->getMemberRegistrationAtDate($now);
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

    /**
     * Set addr3
     *
     * @param string $addr3
     *
     * @return Person
     */
    public function setAddr3($addr3)
    {
        $this->addr3 = $addr3;

        return $this;
    }

    /**
     * Get addr3
     *
     * @return string
     */
    public function getAddr3()
    {
        return $this->addr3;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Person
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }


    public function getJoinedDate()
    {
        $joinedDate = new \DateTime();
        foreach ($this->memberRegistration as $registration) {
            if ($registration->getRegistrationDateTime() < $joinedDate) {
                $joinedDate = $registration->getRegistrationDateTime();
            }
        }

        return $joinedDate;
    }


    public function isAttending($activity)
    {
        foreach ($this->participant as $participant) {
            if ($participant->getManagedActivity() == $activity) {
                return true;
            }
        }

        return false;
    }

    public function getName()
    {
        return $this->forename . ' ' . $this->surname;
    }


    public function getShortName()
    {
        return $this->forename . ' ' . substr($this->surname, 0, 1);
    }

    /**
     * Add charge
     *
     * @param \AppBundle\Entity\Charge $charge
     *
     * @return Person
     */
    public function addCharge(\AppBundle\Entity\Charge $charge)
    {
        $this->charge[] = $charge;

        return $this;
    }

    /**
     * Remove charge
     *
     * @param \AppBundle\Entity\Charge $charge
     */
    public function removeCharge(\AppBundle\Entity\Charge $charge)
    {
        $this->charge->removeElement($charge);
    }

    /**
     * Get charge
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCharge()
    {
        return $this->charge;
    }

    /**
     * Set nextOfKinName
     *
     * @param string $nextOfKinName
     *
     * @return Person
     */
    public function setNextOfKinName($nextOfKinName)
    {
        $this->nextOfKinName = $nextOfKinName;

        return $this;
    }

    /**
     * Get nextOfKinName
     *
     * @return string
     */
    public function getNextOfKinName()
    {
        return $this->nextOfKinName;
    }

    /**
     * Set nextOfKinRelation
     *
     * @param string $nextOfKinRelation
     *
     * @return Person
     */
    public function setNextOfKinRelation($nextOfKinRelation)
    {
        $this->nextOfKinRelation = $nextOfKinRelation;

        return $this;
    }

    /**
     * Get nextOfKinRelation
     *
     * @return string
     */
    public function getNextOfKinRelation()
    {
        return $this->nextOfKinRelation;
    }

    /**
     * Set nextOfKinContactDetails
     *
     * @param string $nextOfKinContactDetails
     *
     * @return Person
     */
    public function setNextOfKinContactDetails($nextOfKinContactDetails)
    {
        $this->nextOfKinContactDetails = $nextOfKinContactDetails;

        return $this;
    }

    /**
     * Get nextOfKinContactDetails
     *
     * @return string
     */
    public function getNextOfKinContactDetails()
    {
        return $this->nextOfKinContactDetails;
    }


    public function isMissingNextOfKinDetails()
    {
        return ($this->nextOfKinName == '' || $this->nextOfKinRelation == '' || $this->nextOfKinContactDetails == '');
    }

    /**
     * Set bcMembershipNumber
     *
     * @param string $bcMembershipNumber
     *
     * @return Person
     */
    public function setBcMembershipNumber($bcMembershipNumber)
    {
        $this->bcMembershipNumber = $bcMembershipNumber;

        return $this;
    }

    /**
     * Get bcMembershipNumber
     *
     * @return string
     */
    public function getBcMembershipNumber()
    {
        return $this->bcMembershipNumber;
    }


    public function getUnpaidCharges()
    {
        $unpaidCharges = [];

        foreach ($this->charge as $charge) {
            if (!$charge->getPaid()) {
                $unpaidCharges[] = $charge;
            }
        }

        return $unpaidCharges;
    }


    /**
     * Add activityMessage
     *
     * @param \AppBundle\Entity\ActivityMessage $activityMessage
     *
     * @return Person
     */
    public function addActivityMessage(\AppBundle\Entity\ActivityMessage $activityMessage)
    {
        $this->activityMessage[] = $activityMessage;

        return $this;
    }

    /**
     * Remove activityMessage
     *
     * @param \AppBundle\Entity\ActivityMessage $activityMessage
     */
    public function removeActivityMessage(\AppBundle\Entity\ActivityMessage $activityMessage)
    {
        $this->activityMessage->removeElement($activityMessage);
    }

    /**
     * Get activityMessage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivityMessage()
    {
        return $this->activityMessage;
    }

    /**
     * Add verifiedMemberQualification
     *
     * @param \AppBundle\Entity\MemberQualification $verifiedMemberQualification
     *
     * @return Person
     */
    public function addVerifiedMemberQualification(\AppBundle\Entity\MemberQualification $verifiedMemberQualification)
    {
        $this->verifiedMemberQualifications[] = $verifiedMemberQualification;

        return $this;
    }

    /**
     * Remove verifiedMemberQualification
     *
     * @param \AppBundle\Entity\MemberQualification $verifiedMemberQualification
     */
    public function removeVerifiedMemberQualification(\AppBundle\Entity\MemberQualification $verifiedMemberQualification)
    {
        $this->verifiedMemberQualifications->removeElement($verifiedMemberQualification);
    }

    /**
     * Get verifiedMemberQualifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVerifiedMemberQualifications()
    {
        return $this->verifiedMemberQualifications;
    }

    /**
     * Set lastLoginDateTime
     *
     * @param \DateTime $lastLoginDateTime
     *
     * @return Person
     */
    public function setLastLoginDateTime($lastLoginDateTime)
    {
        $this->lastLoginDateTime = $lastLoginDateTime;

        return $this;
    }

    /**
     * Get lastLoginDateTime
     *
     * @return \DateTime
     */
    public function getLastLoginDateTime()
    {
        return $this->lastLoginDateTime;
    }
}
