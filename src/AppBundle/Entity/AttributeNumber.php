<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class AttributeNumber extends \AppBundle\Entity\Attribute
{
    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $value;

    /**
     * Set value
     *
     * @param string $value
     *
     * @return AttributeNumber
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
