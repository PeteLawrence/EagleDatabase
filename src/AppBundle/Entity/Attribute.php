<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "B"="AppBundle\Entity\AttributeBoolean",
 *     "T"="AppBundle\Entity\AttributeText",
 *     "N"="AppBundle\Entity\AttributeNumber"
 * })
 */
class Attribute
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PersonAttribute", mappedBy="attribute")
     */
    private $personAttribute;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->personAttribute = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Attribute
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add personAttribute
     *
     * @param \AppBundle\Entity\PersonAttribute $personAttribute
     *
     * @return Attribute
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
}
