<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 *
 *
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
    private $code;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

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

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Attribute
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Attribute
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

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Attribute
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
