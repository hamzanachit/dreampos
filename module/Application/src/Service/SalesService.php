<?php

    namespace Application\Service; 

    use Doctrine\ORM\EntityManager;
    use Application\Entity\Setting;
    use Application\Entity\User;
    use Application\Entity\Orders;
    use Application\Entity\OrderItems;
    use Application\Entity\Company; 
    use Application\Entity\Customers; 
    use Application\Entity\Product; 
    use Application\Entity\BL; 
    use Application\Entity\BLItems; 
    use Application\Entity\Estimates; 
    use Application\Entity\EstimateItems; 

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


        
    public function getProducts($companyId, $query){
        try { 
            $queryBuilder = $this->entityManager->createQueryBuilder();
            $queryBuilder->select('p')
                ->from('Application\Entity\Product', 'p')
                ->where('p.idcompany = :companyId')
                ->andWhere(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('p.name', ':query'),
                        $queryBuilder->expr()->like('p.sku', ':query')
                    )
                )
                ->setParameter('companyId', $companyId)
                ->setParameter('query', '%' . $query . '%');
             $data = $queryBuilder->getQuery()->getResult(); 
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
 
    public function getProductsSl($companyId, $query){
        try { 
            $queryBuilder = $this->entityManager->createQueryBuilder(); 
            $queryBuilder->select('p')
                ->from('Application\Entity\Product', 'p')
                ->where('p.idcompany = :companyId')
                ->andWhere(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('p.name', ':query'),
                        $queryBuilder->expr()->like('p.sku', ':query')
                    )
                )
                ->setParameter('companyId', $companyId)
                ->setParameter('query', '%' . $query . '%'); 
            $data = $queryBuilder->getQuery()->getResult(); 
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

    public function addOrderSL(array $orderData, $idcompany, $createdBy){
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

                // Update stock if the Type is 'SL'
                if ($orderData['type'] === 'SL') {
                    $product = $this->entityManager->getRepository(Product::class)->find($itemData['id']);
                    if ($product) {
                        $newStock = $product->getQuantity() - $itemData['quantity'];
                        $product->setQuantity($newStock);
                        $this->entityManager->persist($product);
                    }
                }
            }

            $this->entityManager->flush();

            return $order;
        } catch (\Exception $e) {
            error_log('Error in addOrder: ' . $e->getMessage());
            error_log($e->getTraceAsString());
            throw $e;
        }
    }

    
    public function addOrderES(array $orderData, $idcompany, $createdBy){
        try {
            $order = new Estimates();
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
                $orderItem = new EstimateItems();
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

                // Update stock if the Type is 'SL'
                if ($orderData['type'] === 'SL') {
                    $product = $this->entityManager->getRepository(Product::class)->find($itemData['id']);
                    if ($product) {
                        $newStock = $product->getQuantity() - $itemData['quantity'];
                        $product->setQuantity($newStock);
                        $this->entityManager->persist($product);
                    }
                }
            }

            $this->entityManager->flush();

            return $order;
        } catch (\Exception $e) {
            error_log('Error in addOrder: ' . $e->getMessage());
            error_log($e->getTraceAsString());
            throw $e;
        }
    }



    public function addOrderBL(array $orderData, $idcompany, $createdBy){
        try {
            $order = new BL();
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
                $orderItem = new BLItems();
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

                // Update stock if the Type is 'SL'
                if ($orderData['type'] === 'SL') {
                    $product = $this->entityManager->getRepository(Product::class)->find($itemData['id']);
                    if ($product) {
                        $newStock = $product->getQuantity() - $itemData['quantity'];
                        $product->setQuantity($newStock);
                        $this->entityManager->persist($product);
                    }
                }
            }

            $this->entityManager->flush();

            return $order;
        } catch (\Exception $e) {
            error_log('Error in addOrder: ' . $e->getMessage());
            error_log($e->getTraceAsString());
            throw $e;
        }
    }

 
    public function getOrdersByCompany($companyId){
     try {
            $query = $this->entityManager->createQueryBuilder()
                ->from('Application\Entity\BL', 's')
                ->innerJoin("Application\Entity\BLItems", "u", "WITH", "u.orderId = s.id")
                ->innerJoin("Application\Entity\Product", "p", "WITH", "p.id = u.productId")
                ->innerJoin("Application\Entity\User", "us", "WITH", "us.id = s.createdBy")
                ->innerJoin("Application\Entity\Customers", "cr", "WITH", "cr.id = s.customer")
                ->select('s')
                ->addSelect('cr.name as customername')
                ->addSelect('p.name, p.price, p.createdAt, p.image, p.barcode, p.CostPrice,p.sku, p.discountType, u.quantity, u.subtotal, u.createdAt, u.updatedAt, p.name, us.fullname, s.type, s.id, s.status, s.orderTax as TAX')
                ->addSelect("(u.subtotal + (u.subtotal * s.orderTax / 100) + s.shipping - (u.subtotal * s.discount / 100)) as grandTotal" )  
                ->addSelect("(u.subtotal  + s.shipping - (u.subtotal * s.discount / 100)) as HtTotal" )  
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



    public function getsalesByCompany($companyId){
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
                    ->andWhere("s.type = 'SL'")
                    ->setParameter('companyId', $companyId);

                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return [];
            }
    }

 

    public function getOrderDetails($orderId, $companyId){
        try {
            $query = $this->entityManager->createQueryBuilder()
                ->from('Application\Entity\Orders', 's')
                ->innerJoin("Application\Entity\OrderItems", "u", "WITH", "u.orderId = s.id")
                ->innerJoin("Application\Entity\Product", "p", "WITH", "p.id = u.productId")
                ->innerJoin("Application\Entity\User", "us", "WITH", "us.id = s.createdBy")
                ->innerJoin("Application\Entity\Customers", "cr", "WITH", "cr.id = s.customer")
                ->select('s.id, s.type, s.status, s.orderTax, s.shipping, cr.name as customername, us.fullname,s.createdAt,s.createdBy,s.discount')
                ->addSelect('u.id as orderItemId, u.quantity, u.subtotal, p.name as productName, p.price as productPrice,p.sku, p.CostPrice, p.discountType, p.image, p.barcode')
                ->addSelect(
                    "(u.subtotal + (u.subtotal * s.orderTax / 100) + s.shipping - (u.subtotal * s.discount / 100)) as grandTotal"
                )
                ->addSelect('cr.id as customer_id')
                ->addSelect('cr.customercode as customercode')
                ->addSelect('u.productId as productId')
                ->addSelect('u.orderId as orderId')
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
                // $orderId = $item['id'];
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
                        'customercode' => $item['customercode'],
                        'customer_id' => $item['customer_id'],
                        'sku' => $item['sku'],
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
                    'orderId' => $item['orderId'],
                    'productId' => $item['productId'],
                        'sku' => $item['sku'],
                ];
            }
            
            return array_values($orders); // Return indexed array of orders
        } catch (\Throwable $th) {
            throw $th;
            return [];
        }
    }

    public function getOrderDetailsBL($orderId, $companyId){
        try {
            $query = $this->entityManager->createQueryBuilder()
                ->from('Application\Entity\BL', 's')
                ->innerJoin("Application\Entity\BLItems", "u", "WITH", "u.orderId = s.id")
                ->innerJoin("Application\Entity\Product", "p", "WITH", "p.id = u.productId")
                ->innerJoin("Application\Entity\User", "us", "WITH", "us.id = s.createdBy")
                ->innerJoin("Application\Entity\Customers", "cr", "WITH", "cr.id = s.customer")
                ->select('s.id, s.type, s.status, s.orderTax, s.shipping, cr.name as customername, us.fullname,s.createdAt,s.createdBy,s.discount')
                ->addSelect('u.id as orderItemId, u.quantity, u.subtotal, p.name as productName, p.price as productPrice,p.sku, p.CostPrice, p.discountType, p.image, p.barcode')
                ->addSelect(
                    "(u.subtotal + (u.subtotal * s.orderTax / 100) + s.shipping - (u.subtotal * s.discount / 100)) as grandTotal"
                )
                ->addSelect('cr.id as customer_id')
                ->addSelect('cr.customercode as customercode')
                ->addSelect('u.productId as productId')
                ->addSelect('u.orderId as orderId')
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
                // $orderId = $item['id'];
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
                        'customercode' => $item['customercode'],
                        'customer_id' => $item['customer_id'],
                        'sku' => $item['sku'],
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
                    'orderId' => $item['orderId'],
                    'productId' => $item['productId'],
                        'sku' => $item['sku'],
                ];
            }
            
            return array_values($orders); // Return indexed array of orders
        } catch (\Throwable $th) {
            throw $th;
            return [];
        }
    }


    // sales data 
    public function getOrderSlDetails($orderId, $companyId){
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
                ->addSelect('cr.customercode as customercode')
                ->where("s.idcompany = :companyId")
                ->andWhere("s.id = :orderId")
                ->andWhere("s.type = 'SL'")
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
                        'customercode' => $item['customercode'],

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


 

    public function getestimateByCompany($companyId){
            try {
                $query = $this->entityManager->createQueryBuilder()
                    ->from('Application\Entity\Estimates', 's')
                    ->innerJoin("Application\Entity\EstimateItems", "u", "WITH", "u.orderId = s.id")
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
                    ->andWhere("s.type = 'ES'")
                    ->setParameter('companyId', $companyId);

                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return [];
            }
    }


    public function editOrder(array $orderData, $idcompany, $createdBy) {
        try {
            
        if( $orderData['type'] === 'BL'){
            $order = $this->entityManager->getRepository(BL::class)->find($orderData['id']);
        }else if( $orderData['type'] === 'ES'){

            $order = $this->entityManager->getRepository(Estimates::class)->find($orderData['id']);

        } else if($orderData['type'] === 'SL'){

            $order = $this->entityManager->getRepository(Orders::class)->find($orderData['id']);
                        
        }


            if (!$order) {
                throw new \Exception('Order not found');
            }

            // Update order details
            $order->setCustomer($orderData['customer']);
            $order->setDate(new \DateTime($orderData['date']));
            $order->setSupplier($orderData['supplier']);
            $order->setStatus($orderData['status']);
            $order->setOrderTax($orderData['orderTax']);
            $order->setDiscount($orderData['discount']);
            $order->setShipping($orderData['shipping']);
            $order->setType($orderData['type']);
            $order->setUpdatedBy($createdBy);
            $order->setUpdatedAt(new \DateTime());

            if( $orderData['type'] === 'BL'){
            $orderItems = $this->entityManager->getRepository(BLItems::class)->findBy(['orderId' => $order->getId()]);
            }else if( $orderData['type'] === 'ES'){
            $orderItems = $this->entityManager->getRepository(EstimateItems::class)->findBy(['orderId' => $order->getId()]);
            }else if($orderData['type'] === 'SL'){
            $orderItems = $this->entityManager->getRepository(OrderItems::class)->findBy(['orderId' => $order->getId()]);                  
            }


            foreach ($orderItems as $item) {
                $this->entityManager->remove($item);
            }

            // Add updated order items
            foreach ($orderData['products'] as $itemData) {
            if($orderData['type'] === 'BL'){
                    $orderItem = new BLItems();

                }else if($orderData['type'] === 'ES'){
                    $orderItem = new EstimateItems();
                }else if($orderData['type'] === 'SL'){
                    $orderItem = new OrderItems();
                }
                
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

                // Update stock if the Type is 'SL'
                if ($orderData['type'] === 'SL') {
                    $product = $this->entityManager->getRepository(Product::class)->find($itemData['id']);
                    if ($product) {
                        $newStock = $product->getQuantity() - $itemData['quantity'];
                        $product->setQuantity($newStock);
                        $this->entityManager->persist($product);
                    }
                }
            }

            $this->entityManager->flush();

            return $order;
        } catch (\Exception $e) {
            error_log('Error in editOrder: ' . $e->getMessage());
            error_log($e->getTraceAsString());
            throw $e;
        }
    }


    
    // public function updateOrder(int $orderId, array $orderData, $idcompany, $createdBy) {
    //     try {
    //         // Find the order based on the type
    //         if ($orderData['type'] === 'BL') {
    //             $order = $this->entityManager->find(BL::class, $orderId);
    //         } elseif ($orderData['type'] === 'ES') {
    //             $order = $this->entityManager->find(Estimates::class, $orderId);
    //         } elseif ($orderData['type'] === 'SL') {
    //             $order = $this->entityManager->find(Orders::class, $orderId);
    //         }

    //         if (!$order) {
    //             throw new \Exception("Order not found");
    //         }

    //         // Update order details
    //         $order->setCustomer($orderData['customer']);
    //         $order->setDate(new \DateTime($orderData['date']));
    //         $order->setSupplier($orderData['supplier']);
    //         $order->setStatus($orderData['status']);
    //         $order->setOrderTax($orderData['orderTax']);
    //         $order->setDiscount($orderData['discount']);
    //         $order->setShipping($orderData['shipping']);
    //         $order->setUpdatedBy($createdBy);
    //         $order->setUpdatedAt(new \DateTime());

    //         foreach ($orderData['products'] as $itemData) {
    //             if (isset($itemData['iditem']) && $itemData['iditem'] != "undefined") {
    //                 // Update existing item
    //                 if ($orderData['type'] === 'BL') {
    //                     $orderItem = $this->entityManager->find(BLItems::class, $itemData['iditem']);
    //                 } elseif ($orderData['type'] === 'ES') {
    //                     $orderItem = $this->entityManager->find(EstimateItems::class, $itemData['iditem']);
    //                 } elseif ($orderData['type'] === 'SL') {
    //                     $orderItem = $this->entityManager->find(OrderItems::class, $itemData['iditem']);
    //                 }

    //                 if (!$orderItem) {
    //                     throw new \Exception("Order item not found");
    //                 }
    //             } else {
    //                 // Create new item
    //                 if ($orderData['type'] === 'BL') {
    //                     $orderItem = new BLItems();
    //                 } elseif ($orderData['type'] === 'ES') {
    //                     $orderItem = new EstimateItems();
    //                 } elseif ($orderData['type'] === 'SL') {
    //                     $orderItem = new OrderItems();
    //                 }
                    
    //                 $orderItem->setOrderId($order->getId());
    //                 $orderItem->setIdcompany($idcompany);
    //                 $orderItem->setCreatedAt(new \DateTime());
    //                 $orderItem->setCreatedBy($createdBy);
    //             }

    //             // Set item details
    //             if (isset($itemData['productId']) && isset($itemData['quantity']) && isset($itemData['price'])) {
    //                 $orderItem->setProductId($itemData['productId']);
    //                 $orderItem->setQuantity($itemData['quantity']);
    //                 $orderItem->setPrice($itemData['price']);
    //                 $orderItem->setDiscount($itemData['discount'] ?? 0);
    //                 $orderItem->setTax($itemData['tax'] ?? 0);
    //                 $orderItem->setSubtotal($itemData['subtotal']);
    //             } else {
    //                 throw new \Exception("Missing required item data (productId, quantity, or price)");
    //             }

    //             $this->entityManager->persist($orderItem);
    //         }

    //         $this->entityManager->flush();

    //         return $order;
    //     } catch (\Exception $e) {
    //         error_log('Error in updateOrder: ' . $e->getMessage());
    //         error_log($e->getTraceAsString());
    //         throw $e;
    //     }
    // }

 
 

    public function updateOrder(int $orderId, array $orderData, $idcompany, $createdBy)
    {
        try {

            $ordertype = $orderData['orderType'];
// dd( $ordertype);

            $oldOrderType = $this->determineOrderType($orderId, $ordertype);
            $oldOrder = $this->getOrderById($orderId, $oldOrderType);

            if (!$oldOrder) {
                throw new \Exception("Order not found");
            }

            if ($oldOrderType !== $orderData['type']) {
                $newOrder = $this->changeOrderType($oldOrder, $orderData, $idcompany, $createdBy);
                return $newOrder;
            }

            $this->updateExistingOrder($oldOrder, $orderData, $createdBy);
            $this->updateOrderItems($oldOrder, $orderData['products'], $idcompany, $createdBy);

            $this->entityManager->flush();

            return $oldOrder;
        } catch (\Exception $e) {
            $this->logError('Error in updateOrder', $e);
            throw $e;
        }
    }

    // private function determineOrderType(int $orderId): string
    // {
    //     if ($this->entityManager->find(BL::class, $orderId)) {
    //         return 'BL';
    //     } elseif ($this->entityManager->find(Estimates::class, $orderId)) {
    //         return 'ES';
    //     } elseif ($this->entityManager->find(Orders::class, $orderId)) {
    //         return 'SL';
    //     }
    //     throw new \Exception("Unable to determine order type for ID: $orderId");
    // }

    private function determineOrderType(int $orderId, $ordertype): string{
    $foundTypes = [];

    if ($this->entityManager->find(BL::class, $orderId) &&  $ordertype == "BL") {
        $foundTypes[] = 'BL';
    }
    if ($this->entityManager->find(Estimates::class, $orderId)&&  $ordertype == "ES") {
        $foundTypes[] = 'ES';
    }
    if ($this->entityManager->find(Orders::class, $orderId)&&  $ordertype == "SL") {
        $foundTypes[] = 'SL';
    }

    if (count($foundTypes) === 1) {
        return $foundTypes[0];
    } elseif (count($foundTypes) > 1) {
        throw new \Exception("Order ID: $orderId exists in multiple tables: " . implode(', ', $foundTypes));
    }

    throw new \Exception("Unable to determine order type for ID: $orderId");
}


    private function getOrderById(int $orderId, string $type)
    {
        $class = $this->getClassForType($type);
        return $this->entityManager->find($class, $orderId);
    }

    private function changeOrderType($oldOrder, array $orderData, $idcompany, $createdBy)
    {
        $newOrder = $this->createNewOrder($orderData, $oldOrder, $createdBy,$idcompany);
        $this->transferOrderItems($oldOrder, $newOrder, $idcompany, $createdBy);
        $this->entityManager->remove($oldOrder);
        $this->entityManager->flush();
        return $newOrder;
    }

    private function createNewOrder(array $orderData, $oldOrder, $createdBy,$idcompany)
    {
        $class = $this->getClassForType($orderData['type']);
        $newOrder = new $class();
        
        $newOrder->setCustomer($oldOrder->getCustomer());
          $newOrder->setIdcompany($idcompany);
        $newOrder->setDate($oldOrder->getDate());
        $newOrder->setSupplier($oldOrder->getSupplier());
        $newOrder->setStatus($orderData['status']);
        $newOrder->setOrderTax($orderData['orderTax']);
        $newOrder->setDiscount($orderData['discount']);
        $newOrder->setShipping($orderData['shipping']);
        $newOrder->setType($orderData['type']);
        $newOrder->setCreatedBy($oldOrder->getCreatedBy());
        $newOrder->setCreatedAt($oldOrder->getCreatedAt());
        $newOrder->setUpdatedBy($createdBy);
        $newOrder->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($newOrder);
        $this->entityManager->flush();
        return $newOrder;
    }

    private function transferOrderItems($oldOrder, $newOrder, $idcompany, $createdBy)
    {
        $oldItems = $this->getOrderItems($oldOrder);
        $newItemClass = $this->getItemClassForType(get_class($newOrder));

        foreach ($oldItems as $oldItem) {
            $newItem = new $newItemClass();
            
            $newItem->setOrderId($newOrder->getId());
            $newItem->setProductId($oldItem->getProductId());
            $newItem->setQuantity($oldItem->getQuantity());
            $newItem->setPrice($oldItem->getPrice());
            $newItem->setDiscount($oldItem->getDiscount());
            $newItem->setTax($oldItem->getTax());
            $newItem->setSubtotal($oldItem->getSubtotal());
            $newItem->setIdcompany($idcompany);
            $newItem->setCreatedBy($oldItem->getCreatedBy());
            $newItem->setCreatedAt($oldItem->getCreatedAt());

            $this->entityManager->persist($newItem);
        }
    }

    private function getOrderItems($order)
    {
        $orderClass = get_class($order);
        $orderId = $order->getId();

        switch ($orderClass) {
            case BL::class:
                return $this->entityManager->getRepository(BLItems::class)->findBy(['orderId' => $orderId]);
            case Estimates::class:
                return $this->entityManager->getRepository(EstimateItems::class)->findBy(['orderId' => $orderId]);
            case Orders::class:
                return $this->entityManager->getRepository(OrderItems::class)->findBy(['orderId' => $orderId]);
            default:
                throw new \Exception("Unknown order class: " . $orderClass);
        }
    }

    private function updateExistingOrder($order, array $orderData, $createdBy)
    {
        $order->setCustomer($orderData['customer']);
        $order->setDate(new \DateTime($orderData['date']));
        $order->setSupplier($orderData['supplier']);
        $order->setStatus($orderData['status']);
        $order->setOrderTax($orderData['orderTax']);
        $order->setDiscount($orderData['discount']);
        $order->setShipping($orderData['shipping']);
        $order->setUpdatedBy($createdBy);
        $order->setUpdatedAt(new \DateTime());
    }

    private function updateOrderItems($order, array $itemsData, $idcompany, $createdBy)
    {
        $itemClass = $this->getItemClassForType(get_class($order));
        foreach ($itemsData as $itemData) {
            if (isset($itemData['iditem']) && $itemData['iditem'] != "undefined") {
                $item = $this->entityManager->find($itemClass, $itemData['iditem']);
                if (!$item) {
                    throw new \Exception("Order item not found");
                }
            } else {
                $item = new $itemClass();
                $item->setOrderId($order->getId());
                $item->setIdcompany($idcompany);
                $item->setCreatedAt(new \DateTime());
                $item->setCreatedBy($createdBy);
            }
            
            $item->setProductId($itemData['productId']);
            $item->setQuantity($itemData['quantity']);
            $item->setPrice($itemData['price']);
            $item->setDiscount($itemData['discount'] ?? 0);
            $item->setTax($itemData['tax'] ?? 0);
            $item->setSubtotal($itemData['subtotal']);

            $this->entityManager->persist($item);
        }
    }

    private function getClassForType(string $type): string
    {
        $classes = [
            'BL' => BL::class,
            'ES' => Estimates::class,
            'SL' => Orders::class,
        ];
        return $classes[$type] ;
    }

    private function getItemClassForType(string $orderClass): string
    {
        $classes = [
            BL::class => BLItems::class,
            Estimates::class => EstimateItems::class,
            Orders::class => OrderItems::class,
        ];
        return $classes[$orderClass] ;
    }

    private function logError(string $message, \Exception $e): void
    {
        error_log($message . ': ' . $e->getMessage());
        error_log($e->getTraceAsString());
    }

 

 

    public function deleteOrderItem($itemId) { 
        $orderItem = $this->entityManager->getRepository(OrderItems::class)->find($itemId);
        if ($orderItem) {
            $this->entityManager->remove($orderItem);
            $this->entityManager->flush();
            return ['success' => true];
        }
        return ['success' => false, 'message' => 'Item not found.'];
    }

    public function deleteOrderItemBL($itemId) { 
        $orderItem = $this->entityManager->getRepository(BLItems::class)->find($itemId);
        if ($orderItem) {
            // Remove the item from the database
            $this->entityManager->remove($orderItem);
            $this->entityManager->flush();
            return ['success' => true];
        }
        return ['success' => false, 'message' => 'Item not found.'];
    }

      public function deleteOrderItemES($itemId) { 
        $orderItem = $this->entityManager->getRepository(EstimateItems::class)->find($itemId);
        if ($orderItem) {
            // Remove the item from the database
            $this->entityManager->remove($orderItem);
            $this->entityManager->flush();
            return ['success' => true];
        }
        return ['success' => false, 'message' => 'Item not found.'];
    }


    public function getOrderDetailsSl($orderId, $companyId){
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
                ->addSelect('cr.id as customer_id')
                ->addSelect('cr.customercode as customercode')
                ->addSelect('u.productId as productId')
                ->addSelect('u.orderId as orderId')
                ->where("s.idcompany = :companyId")
                ->andWhere("s.id = :orderId")
                ->andWhere("s.type = 'SL'")
                ->setParameter('orderId', $orderId)
                ->setParameter('companyId', $companyId);
                // Use getArrayResult to get an array of results
                $data = $query->getQuery()->getResult();
            // Structure the result to include order items
            $orders = [];
            foreach ($data as $item) {
                // $orderId = $item['id'];
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
                        'customercode' => $item['customercode'],
                        'customer_id' => $item['customer_id'],
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
                    'orderId' => $item['orderId'],
                    'productId' => $item['productId'],

                ];
            }
            
            return array_values($orders); // Return indexed array of orders
        } catch (\Throwable $th) {
            throw $th;
            return [];
        }
    }

    public function updateOrderSl(int $orderId, array $orderData, $idcompany, $createdBy){
        try { 
            $order = $this->entityManager->find(Orders::class, $orderData['orderid']);
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
            $order->setUpdatedBy($createdBy);
            $order->setType($orderData['type']);
            $order->setUpdatedAt(new \DateTime());
            foreach ($orderData['products'] as $itemData) {
                if ($itemData['iditem'] != "undefined" ){
                    $orderItem = $this->entityManager->find(OrderItems::class, $itemData['iditem']);
                
                } else {
                    $orderItem = new OrderItems();
                    $orderItem->setOrderId($orderData['orderid']);  
                    $orderItem->setIdcompany($idcompany);  
                    $orderItem->setCreatedAt(new \DateTime());
                    $orderItem->setCreatedBy($createdBy);

                    
                }

                // Set or update order item details
                if (isset($itemData['productId']) && isset($itemData['quantity']) && isset($itemData['price'])) {
                    $orderItem->setProductId($itemData['productId']);
                    $orderItem->setQuantity($itemData['quantity']);
                    $orderItem->setPrice($itemData['price']);
                    $orderItem->setDiscount($itemData['discount'] ?? 0); // Set to 0 if not provided
                    $orderItem->setTax($itemData['tax'] ?? 0); // Set to 0 if not provided
                    $orderItem->setSubtotal($itemData['subtotal']);
                } else {
                    throw new \Exception("Missing required item data (productId, quantity, or price)");
                }

                // Persist the order item
                $this->entityManager->persist($orderItem);
            }

            // Flush changes to the database
            $this->entityManager->flush();

            return $order;
        } catch (\Exception $e) {
            error_log('Error in updateOrder: ' . $e->getMessage());
            error_log($e->getTraceAsString());
            throw $e;
        }
    }



    public function getOrderDetailsEs($orderId, $companyId){
        try {
            $query = $this->entityManager->createQueryBuilder()
                ->from('Application\Entity\Estimates', 's')
                ->innerJoin("Application\Entity\EstimateItems", "u", "WITH", "u.orderId = s.id")
                ->innerJoin("Application\Entity\Product", "p", "WITH", "p.id = u.productId")
                ->innerJoin("Application\Entity\User", "us", "WITH", "us.id = s.createdBy")
                ->innerJoin("Application\Entity\Customers", "cr", "WITH", "cr.id = s.customer")
                ->select('s.id, s.type, s.status, s.orderTax, s.shipping, cr.name as customername, us.fullname,s.createdAt,s.createdBy,s.discount')
                ->addSelect('u.id as orderItemId, u.quantity, u.subtotal, p.name as productName, p.price as productPrice, p.CostPrice, p.discountType, p.image, p.barcode,p.sku')
                ->addSelect("(u.subtotal + (u.subtotal * s.orderTax / 100) + s.shipping - (u.subtotal * s.discount / 100)) as grandTotal"
                )
                ->addSelect('cr.id as customer_id')
                ->addSelect('cr.customercode as customercode')
                ->addSelect('u.productId as productId')
                ->addSelect('u.orderId as orderId')
                ->where("s.idcompany = :companyId")
                ->andWhere("s.id = :orderId")
                ->andWhere("s.type = 'ES'")
                ->setParameter('orderId', $orderId)
                ->setParameter('companyId', $companyId);
                $data = $query->getQuery()->getResult();
            $orders = [];
            foreach ($data as $item) {
                // $orderId = $item['id'];
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
                        'customercode' => $item['customercode'],
                        'customer_id' => $item['customer_id'],
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
                    'orderId' => $item['orderId'],
                    'productId' => $item['productId'],
                    'sku' => $item['sku'],

                ];
            }
            
            return array_values($orders); // Return indexed array of orders
        } catch (\Throwable $th) {
            throw $th;
            return [];
        }
    }




}