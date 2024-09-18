<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Translations
 *
 * @ORM\Table(name="translations", uniqueConstraints={@ORM\UniqueConstraint(name="unique_origin", columns={"origin"})})
 * @ORM\Entity
 */
class Translations
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
     * @ORM\Column(name="origin", type="string", length=155, nullable=false)
     */
    private $origin;

    /**
     * @var string
     *
     * @ORM\Column(name="fr", type="text", length=65535, nullable=false)
     */
    private $fr;

    /**
     * @var string
     *
     * @ORM\Column(name="ar", type="text", length=65535, nullable=false)
     */
    private $ar;
    /**
     * @var string
     *
     * @ORM\Column(name="us", type="text", length=65535, nullable=false)
     */
    private $us;


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
     * Get the value of origin
     *
     * @return  string
     */ 
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set the value of origin
     *
     * @param  string  $origin
     *
     * @return  self
     */ 
    public function setOrigin(string $origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get the value of fr
     *
     * @return  string
     */ 
    public function getFr()
    {
        return $this->fr;
    }

    /**
     * Set the value of fr
     *
     * @param  string  $fr
     *
     * @return  self
     */ 
    public function setFr(string $fr)
    {
        $this->fr = $fr;

        return $this;
    }

    /**
     * Get the value of ar
     *
     * @return  string
     */ 
    public function getAr()
    {
        return $this->ar;
    }

    /**
     * Set the value of ar
     *
     * @param  string  $ar
     *
     * @return  self
     */ 
    public function setAr(string $ar)
    {
        $this->ar = $ar;

        return $this;
    }

    /**
     * Get the value of us
     *
     * @return  string
     */ 
    public function getUs()
    {
        return $this->us;
    }

    /**
     * Set the value of us
     *
     * @param  string  $us
     *
     * @return  self
     */ 
    public function setUs(string $us)
    {
        $this->us = $us;

        return $this;
    }
}