<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class UnmanagedActivity extends \AppBundle\Entity\Activity
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $people;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $disabled;

    /**
     * Set people
     *
     * @param integer $people
     *
     * @return UnmanagedActivity
     */
    public function setPeople($people)
    {
        $this->people = $people;

        return $this;
    }

    /**
     * Get people
     *
     * @return integer
     */
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * Set disabled
     *
     * @param integer $disabled
     *
     * @return UnmanagedActivity
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return integer
     */
    public function getDisabled()
    {
        return $this->disabled;
    }
}
