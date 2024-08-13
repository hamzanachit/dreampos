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


}
