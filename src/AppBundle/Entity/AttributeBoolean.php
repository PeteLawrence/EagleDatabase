<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class AttributeBoolean extends \AppBundle\Entity\Attribute
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
     * @return AttributeBoolean
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
