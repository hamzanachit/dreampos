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


}
