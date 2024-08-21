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
     * @ORM\Column(name="ICE", type="string", length=100, nullable=false)
     */
    private $ICE;

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
     * @var string
     *
     * @ORM\Column(name="blformat", type="string", length=65535, nullable=true)
     */
    private $blformat;

     /**
     * @var string
     *
     * @ORM\Column(name="invoiceformat", type="string", length=65535, nullable=true)
     */
    private $invoiceformat;
    


    /**
     * @var string
     *
     * @ORM\Column(name="codepostal", type="string", length=65535, nullable=true)
     */
    private $codepostal;


      /**
     * @var string
     *
     * @ORM\Column(name="CEO", type="string", length=65535, nullable=true)
     */
    private $CEO;
    



      /**
     * @var string
     *
     * @ORM\Column(name="cnss", type="string", length=65535, nullable=true)
     */
    private $cnss;
    


      /**
     * @var string
     *
     * @ORM\Column(name="Patent", type="string", length=65535, nullable=true)
     */
    private $Patent;
    


      /**
     * @var string
     *
     * @ORM\Column(name="RC", type="string", length=65535, nullable=true)
     */
    private $RC;
    


      /**
     * @var string
     *
     * @ORM\Column(name="NIF", type="string", length=65535, nullable=true)
     */
    private $NIF;
    

      /**
     * @var string
     *
     * @ORM\Column(name="legalEntityType", type="string", length=65535, nullable=true)
     */
    private $legalEntityType;
    
 
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

    /**
     * Get the value of blformat
     *
     * @return  string
     */ 
    public function getBlformat()
    {
        return $this->blformat;
    }

    /**
     * Set the value of blformat
     *
     * @param  string  $blformat
     *
     * @return  self
     */ 
    public function setBlformat(string $blformat)
    {
        $this->blformat = $blformat;

        return $this;
    }

    /**
     * Get the value of invoiceformat
     *
     * @return  string
     */ 
    public function getInvoiceformat()
    {
        return $this->invoiceformat;
    }

    /**
     * Set the value of invoiceformat
     *
     * @param  string  $invoiceformat
     *
     * @return  self
     */ 
    public function setInvoiceformat(string $invoiceformat)
    {
        $this->invoiceformat = $invoiceformat;

        return $this;
    }

    /**
     * Get the value of codepostal
     *
     * @return  string
     */ 
    public function getCodepostal()
    {
        return $this->codepostal;
    }

    /**
     * Set the value of codepostal
     *
     * @param  string  $codepostal
     *
     * @return  self
     */ 
    public function setCodepostal(string $codepostal)
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    /**
     * Get the value of CEO
     *
     * @return  string
     */ 
    public function getCEO()
    {
        return $this->CEO;
    }

    /**
     * Set the value of CEO
     *
     * @param  string  $CEO
     *
     * @return  self
     */ 
    public function setCEO(string $CEO)
    {
        $this->CEO = $CEO;

        return $this;
    }

    /**
     * Get the value of cnss
     *
     * @return  string
     */ 
    public function getCnss()
    {
        return $this->cnss;
    }

    /**
     * Set the value of cnss
     *
     * @param  string  $cnss
     *
     * @return  self
     */ 
    public function setCnss(string $cnss)
    {
        $this->cnss = $cnss;

        return $this;
    }

    /**
     * Get the value of Patent
     *
     * @return  string
     */ 
    public function getPatent()
    {
        return $this->Patent;
    }

    /**
     * Set the value of Patent
     *
     * @param  string  $Patent
     *
     * @return  self
     */ 
    public function setPatent(string $Patent)
    {
        $this->Patent = $Patent;

        return $this;
    }

    

    /**
     * Get the value of RC
     *
     * @return  string
     */ 
    public function getRC()
    {
        return $this->RC;
    }

    /**
     * Set the value of RC
     *
     * @param  string  $RC
     *
     * @return  self
     */ 
    public function setRC(string $RC)
    {
        $this->RC = $RC;

        return $this;
    }

    

    /**
     * Get the value of legalEntityType
     *
     * @return  string
     */ 
    public function getLegalEntityType()
    {
        return $this->legalEntityType;
    }

    /**
     * Set the value of legalEntityType
     *
     * @param  string  $legalEntityType
     *
     * @return  self
     */ 
    public function setLegalEntityType(string $legalEntityType)
    {
        $this->legalEntityType = $legalEntityType;

        return $this;
    }

    /**
     * Get the value of NIF
     *
     * @return  string
     */ 
    public function getNIF()
    {
        return $this->NIF;
    }

    /**
     * Set the value of NIF
     *
     * @param  string  $NIF
     *
     * @return  self
     */ 
    public function setNIF(string $NIF)
    {
        $this->NIF = $NIF;

        return $this;
    }
}