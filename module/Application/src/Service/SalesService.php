<?php

    namespace Application\Service; 

    use Doctrine\ORM\EntityManager;
    use Application\Entity\Setting;
    use Application\Entity\User;
    use Application\Entity\Orders;
    use Application\Entity\OrderItems;
    use Application\Entity\Company; 
    use Application\Entity\Customers; 

    class SalesService{
        
        protected $entityManager;

    public function __construct(EntityManager $entityManager){
            $this->entityManager = $entityManager;
    }



 




     public function getcustomers($idCompany){
            try {
                $query = $this->entityManager->createQueryBuilder()
                        ->from('Application\Entity\Customers','s') 
                        ->select('s')
                        ->Where("s.idcompany = ".$idCompany);
                        // ->AndWhere("s.id = ".$idcompany);
                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return null;
            }
        }


        
  public function getProducts($companyId, $query)
    {
        try {
            $queryBuilder = $this->entityManager->createQueryBuilder()
                ->select('p')
                ->from('Application\Entity\Product', 'p')
                ->where('p.idcompany = :companyId')
                ->andWhere('p.name LIKE :query')
                ->setParameter('companyId', $companyId)
                ->setParameter('query', '%' . $query . '%');

            $data  =$queryBuilder->getQuery()->getResult();


        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'price' => $item->getPrice()
            ];
        }

        return $result;

 

        } catch (\Throwable $th) {
            error_log('Error retrieving products: ' . $th->getMessage());
            return [];
        }
    }

      public function addOrder(array $orderData,$idcompany,$createdBy)
    {
        try {
            $order = new Orders();
            $order->setIdcompany($idcompany);
            $order->setCustomer($orderData['customer']);
            $order->setDate(new \DateTime($orderData['date']));
            $order->setSupplier($orderData['supplier']);
            $order->setStatus($orderData['status']);
            $order->setOrderTax($orderData['orderTax']);
            $order->setDiscount($orderData['discount']);
            $order->setShipping($orderData['shipping']);
            $order->setType($orderData['type']);
            $order->setCreatedBy($createdBy);
            $order->setCreatedAt(new \DateTime());

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            foreach ($orderData['products'] as $itemData) {
                // dd($order->getId());
                $orderItem = new OrderItems();
                $orderItem->setOrderId($order->getId());
                $orderItem->setIdcompany($idcompany);
                $orderItem->setProductId($itemData['id']);
                $orderItem->setQuantity($itemData['quantity']);
                $orderItem->setPrice($itemData['price']);
                $orderItem->setDiscount($itemData['discount']);
                $orderItem->setTax($itemData['tax']);
                $orderItem->setSubtotal($itemData['subtotal']);
                $orderItem->setCreatedBy($createdBy);
                $orderItem->setCreatedAt(new \DateTime());

                $this->entityManager->persist($orderItem);
            }

            $this->entityManager->flush();

            return $order;
        } catch (\Exception $e) {
            error_log('Error in addOrder: ' . $e->getMessage());
            error_log($e->getTraceAsString());
            throw $e;
        }
    }

    public function updateOrder(int $orderId, array $orderData)
    {
        try {
            $order = $this->entityManager->find(Orders::class, $orderId);
            if (!$order) {
                throw new \Exception("Order not found");
            }

            $order->setCustomer($orderData['customer']);
            $order->setDate(new \DateTime($orderData['date']));
            $order->setSupplier($orderData['supplier']);
            $order->setStatus($orderData['status']);
            $order->setOrderTax($orderData['orderTax']);
            $order->setDiscount($orderData['discount']);
            $order->setShipping($orderData['shipping']);
            $order->setUpdatedBy($orderData['updatedBy']);
            $order->setType($orderData['type']);

            $order->setUpdatedAt(new \DateTime());

            $this->entityManager->flush();

            // Clear existing order items
            $existingItems = $this->entityManager->getRepository(OrderItems::class)->findBy(['order' => $order]);
            foreach ($existingItems as $item) {
                $this->entityManager->remove($item);
            }

            // Add updated items
            foreach ($orderData['products'] as $itemData) {
                $orderItem = new OrderItems();
                $orderItem->setOrderId($order);
                $orderItem->setIdcompany($idcompany);
                $orderItem->setProductId($itemData['id']);
                $orderItem->setQuantity($itemData['quantity']);
                $orderItem->setPrice($itemData['price']);
                $orderItem->setDiscount($itemData['discount']);
                $orderItem->setTax($itemData['tax']);
                $orderItem->setSubtotal($itemData['subtotal']);
                $orderItem->setCreatedBy($createdBy);
                $orderItem->setCreatedAt(new \DateTime());

                $this->entityManager->persist($orderItem);
            }

            $this->entityManager->flush();

            return $order;
        } catch (\Exception $e) {
            error_log('Error in updateOrder: ' . $e->getMessage());
            error_log($e->getTraceAsString());
            throw $e;
        }
    }


 public function getOrdersByCompany($companyId){
    try {
        $query = $this->entityManager->createQueryBuilder()
            ->from('Application\Entity\Orders', 's')
            ->innerJoin("Application\Entity\OrderItems", "u", "WITH", "u.orderId = s.id")
            ->innerJoin("Application\Entity\Product", "p", "WITH", "p.id = u.productId")
            ->innerJoin("Application\Entity\User", "us", "WITH", "us.id = s.createdBy")
            ->innerJoin("Application\Entity\Customers", "cr", "WITH", "cr.id = s.customer")
            ->select('s')
            ->addSelect('cr.name as customername')
            ->addSelect('p.name, p.price, p.createdAt, p.image, p.barcode, p.CostPrice, p.discountType, u.quantity, u.subtotal, u.createdAt, u.updatedAt, p.name, us.fullname, s.type, s.id, s.status, s.orderTax as TAX')
            ->addSelect(
                "(u.subtotal + (u.subtotal * s.orderTax / 100) + s.shipping - (u.subtotal * s.discount / 100)) as grandTotal"
            )  
            ->where("s.idcompany = :companyId")
            ->andWhere("s.type = 'BL'")
            ->setParameter('companyId', $companyId);

        $data = $query->getQuery()->getResult();
        return $data;
    } catch (\Throwable $th) {
        throw $th;
        return [];
    }
}







// public function getOrderDetails($orderId,$companyId){
//     try {
//         $query = $this->entityManager->createQueryBuilder()
//             ->from('Application\Entity\Orders', 's')
//             ->innerJoin("Application\Entity\OrderItems", "u", "WITH", "u.orderId = s.id")
//             ->innerJoin("Application\Entity\Product", "p", "WITH", "p.id = u.productId")
//             ->innerJoin("Application\Entity\User", "us", "WITH", "us.id = s.createdBy")
//             ->innerJoin("Application\Entity\Customers", "cr", "WITH", "cr.id = s.customer")
//             ->select('s')
//             ->addSelect('cr.name as customername')
//             ->addSelect('p.name, p.price, p.createdAt, p.image, p.barcode, p.CostPrice, p.discountType, u.quantity, u.subtotal, u.createdAt, u.updatedAt, p.name, us.fullname, s.type, s.id, s.status, s.orderTax as TAX')
//             ->addSelect(
//                 "(u.subtotal + (u.subtotal * s.orderTax / 100) + s.shipping - (u.subtotal * s.discount / 100)) as grandTotal"
//             ) // Grand total calculation
//             ->where("s.idcompany = :companyId")
//             ->andwhere("s.id = :orderId")
//             ->andWhere("s.type = 'BL'")
//             ->setParameter('orderId', $orderId)
//             ->setParameter('companyId', $companyId);

//         $data = $query->getQuery()->getResult();
//         return $data;
//     } catch (\Throwable $th) {
//         throw $th;
//         return [];
//     }
// }





public function getOrderDetails($orderId, $companyId)
{
    try {
        $query = $this->entityManager->createQueryBuilder()
            ->from('Application\Entity\Orders', 's')
            ->innerJoin("Application\Entity\OrderItems", "u", "WITH", "u.orderId = s.id")
            ->innerJoin("Application\Entity\Product", "p", "WITH", "p.id = u.productId")
            ->innerJoin("Application\Entity\User", "us", "WITH", "us.id = s.createdBy")
            ->innerJoin("Application\Entity\Customers", "cr", "WITH", "cr.id = s.customer")
            ->select('s.id, s.type, s.status, s.orderTax, s.shipping, cr.name as customername, us.fullname,s.createdAt,s.createdBy,s.discount')


            ->addSelect('u.id as orderItemId, u.quantity, u.subtotal, p.name as productName, p.price as productPrice, p.CostPrice, p.discountType, p.image, p.barcode')
            ->addSelect(
                "(u.subtotal + (u.subtotal * s.orderTax / 100) + s.shipping - (u.subtotal * s.discount / 100)) as grandTotal"
            )
            ->where("s.idcompany = :companyId")
            ->andWhere("s.id = :orderId")
            ->andWhere("s.type = 'BL'")
            ->setParameter('orderId', $orderId)
            ->setParameter('companyId', $companyId);
            // Use getArrayResult to get an array of results
            $data = $query->getQuery()->getResult();
        // Structure the result to include order items
        $orders = [];
        foreach ($data as $item) {
            $orderId = $item['id'];
            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'id' => $orderId,
                    'type' => $item['type'],
                    'status' => $item['status'],
                    'orderTax' => $item['orderTax'],
                    'shipping' => $item['shipping'],
                    'customername' => $item['customername'],
                    'fullname' => $item['fullname'],
                    'grandTotal' => $item['grandTotal'],
                    'createdAt' => $item['createdAt'],
                    'price' => $item['productPrice'],
                    'name' => $item['productName'],
                    'discount' => $item['discount'],

                    'items' => []  
                ];
            }
            
            // Add order item details
            $orders[$orderId]['items'][] = [
                'orderItemId' => $item['orderItemId'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
                'productName' => $item['productName'],
                'productPrice' => $item['productPrice'],
                'CostPrice' => $item['CostPrice'],
                'discountType' => $item['discountType'],
                'image' => $item['image'],
                'barcode' => $item['barcode'],
                'createdAt' => $item['createdAt'],
                'price' => $item['productPrice'],
                'name' => $item['productName'],
                'TAX' => $item['orderTax'],
                'discount' => $item['discount'],

            ];
        }
        
        return array_values($orders); // Return indexed array of orders
    } catch (\Throwable $th) {
        throw $th;
        return [];
    }
}









    //  public function getrefByCompany($companyId){
    //         try {
    //             $query = $this->entityManager->createQueryBuilder()
    //                     ->from('Application\Entity\Orders','s')
    //                     ->innerJoin("Application\Entity\OrderItems", "u","WITH", "u.orderId = s.id")
    //                     ->innerJoin("Application\Entity\Product", "p","WITH", "p.id = u.productId")
    //                     ->innerJoin("Application\Entity\User", "us","WITH", "us.id = s.createdBy")
    //                     ->select('s')
    //                     // ->addselect('u')
    //                     ->addselect('p.name,p.price,p.createdAt,p.image,p.barcode,p.CostPrice,p.discountType,u.quantity,u.subtotal,u.createdAt,u.updatedAt,p.name,us.fullname')
    //                     ->Where("s.idcompany = ".$companyId);
    //             $data = $query->getQuery()->getResult();
    //             return $data;
    //         } catch (\Throwable $th) {
    //             throw $th;
    //             return [];
    //         }
    // }

    // public function getOrdersByCompany($companyId)
    // {

    //     try {
    //         $OrdersRepository = $this->entityManager->getRepository(Orders::class);
    //         $Orders = $OrdersRepository->find($companyId);
    //         return $Orders;
    //     } catch (\Exception $e) { 
    //         return null;
    //     }
    // }
}