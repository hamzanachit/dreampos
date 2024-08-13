<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderItems
 *
 * @ORM\Table(name="order_items", indexes={@ORM\Index(name="created_by", columns={"created_by"}), @ORM\Index(name="idcompany", columns={"idcompany"}), @ORM\Index(name="order_id", columns={"order_id"}), @ORM\Index(name="updated_by", columns={"updated_by"})})
 * @ORM\Entity
 */
class OrderItems
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
     * @var int
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false)
     */
    private $orderId;

    /**
     * @var int
     *
     * @ORM\Column(name="idcompany", type="integer", nullable=false)
     */
    private $idcompany;

    /**
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="discount", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(name="tax", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $tax;

    /**
     * @var string
     *
     * @ORM\Column(name="subtotal", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $subtotal;

    /**
     * @var int
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

    /**
     * @var int|null
     *
     * @ORM\Column(name="updated_by", type="integer", nullable=true)
     */
    private $updatedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;



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
     * Get the value of orderId
     *
     * @return  int
     */ 
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set the value of orderId
     *
     * @param  int  $orderId
     *
     * @return  self
     */ 
    public function setOrderId(int $orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get the value of idcompany
     *
     * @return  int
     */ 
    public function getIdcompany()
    {
        return $this->idcompany;
    }

    /**
     * Set the value of idcompany
     *
     * @param  int  $idcompany
     *
     * @return  self
     */ 
    public function setIdcompany(int $idcompany)
    {
        $this->idcompany = $idcompany;

        return $this;
    }

    /**
     * Get the value of productId
     *
     * @return  int
     */ 
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set the value of productId
     *
     * @param  int  $productId
     *
     * @return  self
     */ 
    public function setProductId(int $productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get the value of quantity
     *
     * @return  int
     */ 
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     * @param  int  $quantity
     *
     * @return  self
     */ 
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get the value of price
     *
     * @return  string
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param  string  $price
     *
     * @return  self
     */ 
    public function setPrice(string $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of discount
     *
     * @return  string
     */ 
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set the value of discount
     *
     * @param  string  $discount
     *
     * @return  self
     */ 
    public function setDiscount(string $discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get the value of tax
     *
     * @return  string
     */ 
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set the value of tax
     *
     * @param  string  $tax
     *
     * @return  self
     */ 
    public function setTax(string $tax)
    {
        $this->tax = $tax;

        return $this;
    }
    
    /**
     * Set the value of subtotal
     *
     * @param  string  $subtotal
     *
     * @return  self
     */ 
    public function setSubtotal(string $subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get the value of subtotal
     *
     * @return  string
     */ 
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    
    /**
     * Get the value of createdBy
     *
     * @return  int
     */ 
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set the value of createdBy
     *
     * @param  int  $createdBy
     *
     * @return  self
     */ 
    public function setCreatedBy(int $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get the value of updatedBy
     *
     * @return  int|null
     */ 
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set the value of updatedBy
     *
     * @param  int|null  $updatedBy
     *
     * @return  self
     */ 
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get the value of createdAt
     *
     * @return  \DateTime
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param  \DateTime  $createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt(\DateTime $createdAt)
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