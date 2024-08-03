<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalesOrderItems
 *
 * @ORM\Table(name="sales_order_items", indexes={@ORM\Index(name="order_id", columns={"order_id"}), @ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class SalesOrderItems
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
     * @ORM\Column(name="unit_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $unitPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="total_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $totalPrice;


}
