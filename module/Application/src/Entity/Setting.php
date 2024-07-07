<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Setting
 *
 * @ORM\Table(name="setting")
 * @ORM\Entity
 */
class Setting
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
     * @ORM\Column(name="company_name", type="string", length=80, nullable=false)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=200, nullable=false)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=80, nullable=false)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="sku_format", type="text", length=65535, nullable=false)
     */
    private $skuFormat;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=100, nullable=false)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="dark_mode", type="string", length=100, nullable=false)
     */
    private $darkMode;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="text", length=65535, nullable=false)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="text", length=65535, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="company_city", type="text", length=65535, nullable=false)
     */
    private $companyCity;

    /**
     * @var string
     *
     * @ORM\Column(name="company_addresse", type="text", length=65535, nullable=false)
     */
    private $companyAddresse;

    /**
     * @var string
     *
     * @ORM\Column(name="company_phone", type="text", length=65535, nullable=false)
     */
    private $companyPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="company_email", type="text", length=65535, nullable=false)
     */
    private $companyEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="company_Status", type="text", length=65535, nullable=false)
     */
    private $companyStatus;

     /**
     * @var string
     *
     * @ORM\Column(name="creator", type="integer", length=65535, nullable=false)
     */
    private $creator;




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
     * Get the value of companyName
     *
     * @return  string
     */ 
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set the value of companyName
     *
     * @param  string  $companyName
     *
     * @return  self
     */ 
    public function setCompanyName(string $companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get the value of logo
     *
     * @return  string
     */ 
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set the value of logo
     *
     * @param  string  $logo
     *
     * @return  self
     */ 
    public function setLogo(string $logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get the value of language
     *
     * @return  string
     */ 
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set the value of language
     *
     * @param  string  $language
     *
     * @return  self
     */ 
    public function setLanguage(string $language)
    {
        $this->language = $language;

        return $this;
    }

   
    /**
     * Get the value of skuFormat
     *
     * @return  string
     */ 
    public function getSkuFormat()
    {
        return $this->skuFormat;
    }

    /**
     * Set the value of skuFormat
     *
     * @param  string  $skuFormat
     *
     * @return  self
     */ 
    public function setSkuFormat(string $skuFormat)
    {
        $this->skuFormat = $skuFormat;

        return $this;
    }

    /**
     * Get the value of color
     *
     * @return  string
     */ 
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @param  string  $color
     *
     * @return  self
     */ 
    public function setColor(string $color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get the value of darkMode
     *
     * @return  string
     */ 
    public function getDarkMode()
    {
        return $this->darkMode;
    }

    /**
     * Set the value of darkMode
     *
     * @param  string  $darkMode
     *
     * @return  self
     */ 
    public function setDarkMode(string $darkMode)
    {
        $this->darkMode = $darkMode;

        return $this;
    }

    /**
     * Get the value of currency
     *
     * @return  string
     */ 
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set the value of currency
     *
     * @param  string  $currency
     *
     * @return  self
     */ 
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the value of country
     *
     * @return  string
     */ 
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @param  string  $country
     *
     * @return  self
     */ 
    public function setCountry(string $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the value of companyCity
     *
     * @return  string
     */ 
    public function getCompanyCity()
    {
        return $this->companyCity;
    }

    /**
     * Set the value of companyCity
     *
     * @param  string  $companyCity
     *
     * @return  self
     */ 
    public function setCompanyCity(string $companyCity)
    {
        $this->companyCity = $companyCity;

        return $this;
    }

    /**
     * Get the value of companyAddresse
     *
     * @return  string
     */ 
    public function getCompanyAddresse()
    {
        return $this->companyAddresse;
    }

    /**
     * Set the value of companyAddresse
     *
     * @param  string  $companyAddresse
     *
     * @return  self
     */ 
    public function setCompanyAddresse(string $companyAddresse)
    {
        $this->companyAddresse = $companyAddresse;

        return $this;
    }

    /**
     * Get the value of companyPhone
     *
     * @return  string
     */ 
    public function getCompanyPhone()
    {
        return $this->companyPhone;
    }

    /**
     * Set the value of companyPhone
     *
     * @param  string  $companyPhone
     *
     * @return  self
     */ 
    public function setCompanyPhone(string $companyPhone)
    {
        $this->companyPhone = $companyPhone;

        return $this;
    }

    /**
     * Get the value of companyEmail
     *
     * @return  string
     */ 
    public function getCompanyEmail()
    {
        return $this->companyEmail;
    }

    /**
     * Set the value of companyEmail
     *
     * @param  string  $companyEmail
     *
     * @return  self
     */ 
    public function setCompanyEmail(string $companyEmail)
    {
        $this->companyEmail = $companyEmail;

        return $this;
    }

    /**
     * Get the value of companyStatus
     *
     * @return  string
     */ 
    public function getCompanyStatus()
    {
        return $this->companyStatus;
    }

    /**
     * Set the value of companyStatus
     *
     * @param  string  $companyStatus
     *
     * @return  self
     */ 
    public function setCompanyStatus(string $companyStatus)
    {
        $this->companyStatus = $companyStatus;

        return $this;
    }

    /**
     * Get the value of creator
     *
     * @return  string
     */ 
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set the value of creator
     *
     * @param  string  $creator
     *
     * @return  self
     */ 
    public function setCreator(string $creator)
    {
        $this->creator = $creator;

        return $this;
    }
}