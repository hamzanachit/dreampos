<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders", indexes={@ORM\Index(name="created_by", columns={"created_by"}), @ORM\Index(name="idcompany", columns={"idcompany"}), @ORM\Index(name="updated_by", columns={"updated_by"})})
 * @ORM\Entity
 */
class Orders
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
     * @ORM\Column(name="idcompany", type="integer", nullable=false)
     */
    private $idcompany;

    /**
     * @var string
     *
     * @ORM\Column(name="customer", type="string", length=255, nullable=false)
     */
    private $customer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="supplier", type="string", length=255, nullable=false)
     */
    private $supplier;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="order_tax", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $orderTax;

    /**
     * @var string
     *
     * @ORM\Column(name="discount", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $shipping;

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
     * @var string
     *
     * @ORM\Column(name="type", type="string", precision=10, scale=2, nullable=true)
     */
    private $type;

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
     * Get the value of customer
     *
     * @return  string
     */ 
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set the value of customer
     *
     * @param  string  $customer
     *
     * @return  self
     */ 
    public function setCustomer(string $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get the value of date
     *
     * @return  \DateTime
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @param  \DateTime  $date
     *
     * @return  self
     */ 
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of supplier
     *
     * @return  string
     */ 
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set the value of supplier
     *
     * @param  string  $supplier
     *
     * @return  self
     */ 
    public function setSupplier(string $supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  string
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  string  $status
     *
     * @return  self
     */ 
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of orderTax
     *
     * @return  string
     */ 
    public function getOrderTax()
    {
        return $this->orderTax;
    }

    /**
     * Set the value of orderTax
     *
     * @param  string  $orderTax
     *
     * @return  self
     */ 
    public function setOrderTax(string $orderTax)
    {
        $this->orderTax = $orderTax;

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
     * Get the value of shipping
     *
     * @return  string
     */ 
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * Set the value of shipping
     *
     * @param  string  $shipping
     *
     * @return  self
     */ 
    public function setShipping(string $shipping)
    {
        $this->shipping = $shipping;

        return $this;
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

  

    /**
     * Get the value of type
     *
     * @return  string
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param  string  $type
     *
     * @return  self
     */ 
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }
}