<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class AttributeText extends \AppBundle\Entity\Attribute
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;

    /**
     * Set value
     *
     * @param string $value
     *
     * @return AttributeText
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
