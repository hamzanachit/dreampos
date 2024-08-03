<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity
 */
class Company
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_company", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCompany;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="text", length=65535, nullable=false)
     */
    private $companyName;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;



    /**
     * Get the value of idCompany
     */
    public function getIdCompany() {
        return $this->idCompany;
    }

    
    /**
     * Get the value of companyName
     */
    public function getCompanyName() {
        return $this->companyName;
    }

    /**
     * Set the value of companyName
     */
    public function setCompanyName($companyName): self {
        $this->companyName = $companyName;
        return $this;
    }



      /**
     * Get the value of userId
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set the value of userId
     */
    public function setUserId($userId): self {
        $this->userId = $userId;
        return $this;
    }

}