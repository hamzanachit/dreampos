<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customers
 *
 * @ORM\Table(name="customers")
 * @ORM\Entity
 */
class Customers
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="text", length=65535, nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="total_purchases", type="decimal", precision=10, scale=2, nullable=true, options={"default"="0.00"})
     */
    private $totalPurchases = '0.00';

    /**
     * @var string|null
     *
     * @ORM\Column(name="max_purchase_amount", type="decimal", precision=10, scale=2, nullable=true, options={"default"="0.00"})
     */
    private $maxPurchaseAmount = '0.00';

    /**
     * @var int
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

    /**
     * @var int
     *
     * @ORM\Column(name="updatedBy", type="integer", nullable=true)
     */
    private $updatedBy;

    /**
     * @var int
     *
     * @ORM\Column(name="idcompany", type="integer", nullable=false)
     */
    private $idcompany;

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
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="ICE", type="string", nullable=true)
     */
    private $ICE;

     /**
     * @var int
     *
     * @ORM\Column(name="bank", type="integer", nullable=true)
     */
    private $bank;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", nullable=true)
     */
    private $note;

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
     * Get the value of email
     *
     * @return  string
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     *
     * @return  self
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of phone
     *
     * @return  string|null
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @param  string|null  $phone
     *
     * @return  self
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of address
     *
     * @return  string|null
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @param  string|null  $address
     *
     * @return  self
     */ 
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of totalPurchases
     *
     * @return  string|null
     */ 
    public function getTotalPurchases()
    {
        return $this->totalPurchases;
    }

    /**
     * Set the value of totalPurchases
     *
     * @param  string|null  $totalPurchases
     *
     * @return  self
     */ 
    public function setTotalPurchases($totalPurchases)
    {
        $this->totalPurchases = $totalPurchases;

        return $this;
    }

    /**
     * Get the value of maxPurchaseAmount
     *
     * @return  string|null
     */ 
    public function getMaxPurchaseAmount()
    {
        return $this->maxPurchaseAmount;
    }

    /**
     * Set the value of maxPurchaseAmount
     *
     * @param  string|null  $maxPurchaseAmount
     *
     * @return  self
     */ 
    public function setMaxPurchaseAmount($maxPurchaseAmount)
    {
        $this->maxPurchaseAmount = $maxPurchaseAmount;

        return $this;
    }

    
   
    
    /**
     * Get the value of note
     *
     * @return  string
     */ 
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set the value of note
     *
     * @param  string  $note
     *
     * @return  self
     */ 
    public function setNote(string $note)
    {
        $this->note = $note;

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
     * Get the value of bank
     *
     * @return  int
     */ 
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set the value of bank
     *
     * @param  int  $bank
     *
     * @return  self
     */ 
    public function setBank(int $bank)
    {
        $this->bank = $bank;

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

    /**
     * Get the value of ICE
     *
     * @return  string
     */ 
    public function getICE()
    {
        return $this->ICE;
    }

    /**
     * Set the value of ICE
     *
     * @param  string  $ICE
     *
     * @return  self
     */ 
    public function setICE(string $ICE)
    {
        $this->ICE = $ICE;

        return $this;
    }

    /**
     * Get the value of updatedBy
     *
     * @return  int
     */ 
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set the value of updatedBy
     *
     * @param  int  $updatedBy
     *
     * @return  self
     */ 
    public function setUpdatedBy(int $updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}