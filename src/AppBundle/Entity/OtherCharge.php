<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class OtherCharge extends \AppBundle\Entity\Charge
{
    /**
     * 
     */
    private $description;

    /**
     * Set description
     *
     * @param string $description
     *
     * @return OtherCharge
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
}
