<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AttributeValueBoolean extends \AppBundle\Entity\AttributeValue
{
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $value;

    /**
     * Set value
     *
     * @param boolean $value
     *
     * @return AttributeValueBoolean
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return boolean
     */
    public function getValue()
    {
        return $this->value;
    }
}
