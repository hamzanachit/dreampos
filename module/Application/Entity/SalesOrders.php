<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalesOrders
 *
 * @ORM\Table(name="sales_orders", indexes={@ORM\Index(name="customer_id", columns={"customer_id"})})
 * @ORM\Entity
 */
class SalesOrders
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
     * @ORM\Column(name="order_number", type="string", length=50, nullable=false)
     */
    private $orderNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="customer_id", type="integer", nullable=false)
     */
    private $customerId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_date", type="datetime", nullable=false)
     */
    private $orderDate;

    /**
     * @var string
     *
     * @ORM\Column(name="total_amount", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $totalAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=false)
     */
    private $status;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';


}
