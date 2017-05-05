<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ActivityCharge extends \AppBundle\Entity\Charge
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ManagedActivity", inversedBy="activityCharge")
     * @ORM\JoinColumn(name="managed_activity_id", referencedColumnName="id")
     */
    private $managedActivity;

    /**
     * Set managedActivity
     *
     * @param \AppBundle\Entity\ManagedActivity $managedActivity
     *
     * @return ActivityCharge
     */
    public function setManagedActivity(\AppBundle\Entity\ManagedActivity $managedActivity = null)
    {
        $this->managedActivity = $managedActivity;

        return $this;
    }

    /**
     * Get managedActivity
     *
     * @return \AppBundle\Entity\ManagedActivity
     */
    public function getManagedActivity()
    {
        return $this->managedActivity;
    }
}
