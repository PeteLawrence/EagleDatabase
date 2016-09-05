<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class PersonAttributeText extends \AppBundle\Entity\PersonAttribute
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
     * @return PersonAttributeText
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
