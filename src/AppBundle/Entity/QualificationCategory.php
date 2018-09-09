<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QualificationCategory
 *
 * @ORM\Table(name="qualification_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QualificationCategoryRepository")
 */
class QualificationCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Qualification", mappedBy="qualificationCategory")
     */
    private $qualification;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category.
     *
     * @param string $category
     *
     * @return QualificationCategory
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->qualification = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add qualification.
     *
     * @param \AppBundle\Entity\Qualification $qualification
     *
     * @return QualificationCategory
     */
    public function addQualification(\AppBundle\Entity\Qualification $qualification)
    {
        $this->qualification[] = $qualification;

        return $this;
    }

    /**
     * Remove qualification.
     *
     * @param \AppBundle\Entity\Qualification $qualification
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeQualification(\AppBundle\Entity\Qualification $qualification)
    {
        return $this->qualification->removeElement($qualification);
    }

    /**
     * Get qualification.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQualification()
    {
        return $this->qualification;
    }
}
