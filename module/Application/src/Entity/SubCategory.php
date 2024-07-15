<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubCategory
 *
 * @ORM\Table(name="SubCategory")
 * @ORM\Entity
 */
class SubCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="SubCategory_name", type="text", length=65535, nullable=false)
     */
    private $SubCategoryName;

    /**
     * @var string
     *
     * @ORM\Column(name="IdCategory", type="text", length=65535, nullable=false)
     */
    private $IdCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="created_by", type="string", length=20, nullable=false)
     */
    private $createdBy;

    

    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of SubCategoryName
     *
     * @return  string
     */ 
    public function getSubCategoryName()
    {
        return $this->SubCategoryName;
    }

    /**
     * Set the value of SubCategoryName
     *
     * @param  string  $SubCategoryName
     *
     * @return  self
     */ 
    public function setSubCategoryName(string $SubCategoryName)
    {
        $this->SubCategoryName = $SubCategoryName;

        return $this;
    }

    /**
     * Get the value of IdCategory
     *
     * @return  string
     */ 
    public function getIdCategory()
    {
        return $this->IdCategory;
    }

    /**
     * Set the value of IdCategory
     *
     * @param  string  $IdCategory
     *
     * @return  self
     */ 
    public function setIdCategory(string $IdCategory)
    {
        $this->IdCategory = $IdCategory;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of createdBy
     *
     * @return  string
     */ 
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set the value of createdBy
     *
     * @param  string  $createdBy
     *
     * @return  self
     */ 
    public function setCreatedBy(string $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

}