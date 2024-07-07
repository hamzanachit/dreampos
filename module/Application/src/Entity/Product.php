<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="category", type="string", length=100, nullable=true)
     */
    private $category;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sub_category", type="string", length=100, nullable=true)
     */
    private $subCategory;

    /**
     * @var string|null
     *
     * @ORM\Column(name="brand", type="string", length=100, nullable=true)
     */
    private $brand;

    /**
     * @var string|null
     *
     * @ORM\Column(name="created_by", type="string", length=100, nullable=true)
     */
    private $createdBy;

    /**
     * @var string|null
     *
     * @ORM\Column(name="unit", type="string", length=50, nullable=true)
     */
    private $unit;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sku", type="string", length=100, nullable=true)
     */
    private $sku;

    /**
     * @var int|null
     *
     * @ORM\Column(name="min_qty", type="integer", nullable=true)
     */
    private $minQty;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tax", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $tax;

    /**
     * @var string|null
     *
     * @ORM\Column(name="discount_type", type="string", length=50, nullable=true)
     */
    private $discountType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $price;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=true)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt ;



    /**
     * Get the value of id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of category
     *
     * @return  string|null
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param  string|null  $category
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of subCategory
     *
     * @return  string|null
     */ 
    public function getSubCategory()
    {
        return $this->subCategory;
    }

    /**
     * Set the value of subCategory
     *
     * @param  string|null  $subCategory
     *
     * @return  self
     */ 
    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * Get the value of brand
     *
     * @return  string|null
     */ 
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set the value of brand
     *
     * @param  string|null  $brand
     *
     * @return  self
     */ 
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get the value of createdBy
     *
     * @return  string|null
     */ 
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set the value of createdBy
     *
     * @param  string|null  $createdBy
     *
     * @return  self
     */ 
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get the value of unit
     */
    public function getUnit() {
        return $this->unit;
    }

    /**
     * Set the value of unit
     */
    public function setUnit($unit): self {
        $this->unit = $unit;
        return $this;
    }

    /**
     * Get the value of sku
     */
    public function getSku() {
        return $this->sku;
    }

    /**
     * Set the value of sku
     */
    public function setSku($sku): self {
        $this->sku = $sku;
        return $this;
    }

    /**
     * Get the value of minQty
     */
    public function getMinQty() {
        return $this->minQty;
    }

    /**
     * Set the value of minQty
     */
    public function setMinQty($minQty): self {
        $this->minQty = $minQty;
        return $this;
    }

    /**
     * Get the value of quantity
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     */
    public function setQuantity($quantity): self {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription($description): self {
        $this->description = $description;
        return $this;
    }

    /**
     * Get the value of tax
     */
    public function getTax() {
        return $this->tax;
    }

    /**
     * Set the value of tax
     */
    public function setTax($tax): self {
        $this->tax = $tax;
        return $this;
    }

    /**
     * Get the value of discountType
     */
    public function getDiscountType() {
        return $this->discountType;
    }

    /**
     * Set the value of discountType
     */
    public function setDiscountType($discountType): self {
        $this->discountType = $discountType;
        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set the value of price
     */
    public function setPrice($price): self {
        $this->price = $price;
        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus($status): self {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set the value of image
     */
    public function setImage($image): self {
        $this->image = $image;
        return $this;
    }

    /**
     * Get the value of createdAt
     *
     * @return  \DateTime|null
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param  \DateTime|null  $createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     *
     * @return  \DateTime|null
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @param  \DateTime|null  $updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}