<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\Order;
use Application\Entity\Setting;
use Application\Entity\Product;

class DashboardService
{
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTotalOrders()
    {
        try {
            $orderRepository = $this->entityManager->getRepository(Order::class);
            $totalOrders = $orderRepository->createQueryBuilder('o')
                ->select('COUNT(o.id)')
                ->getQuery()
                ->getSingleScalarResult();
            return $totalOrders;
        } catch (\Exception $e) { 
            return 0;
        }
    }

    public function getTotalProducts($limit = 5){
        try {
            $orderRepository = $this->entityManager->getRepository(Product::class);
            $latestOrders = $orderRepository->findBy([], ['createdAt' => 'DESC'], $limit);

            return $latestOrders;
        } catch (\Exception $e) { 
            return [];
        }
    }


    public function getAllProducts($idcompany) {
        try {
            $queryBuilder = $this->entityManager->getRepository(Product::class)->createQueryBuilder('p');
            $queryBuilder->where('p.idcompany = :idcompany')
                        //  ->andWhere('p.iduser = :iduser')
                        ->setParameter('idcompany', $idcompany);
                        //  ->setParameter('iduser', $iduser);

            $products = $queryBuilder->getQuery()->getResult();
            return $products;
        } catch (\Throwable $th) {
            throw $th;
            return null;
        }
    }


    public function getCheckCompanyById($userid){ 
        $setting = $this->entityManager->getRepository(Setting::class)->findOneBy(['creator' => $userid]);
        
        if ($setting === null) {
            return null; 
        } else {
            return $setting; 
        }
    }




   public function getOrdersByCompany($companyId)
{
    try {
        $query = $this->entityManager->createQueryBuilder()
            ->from('Application\Entity\BL', 's')
            ->innerJoin("Application\Entity\BLItems", "u", "WITH", "u.orderId = s.id")
            ->innerJoin("Application\Entity\Product", "p", "WITH", "p.id = u.productId")
            ->innerJoin("Application\Entity\User", "us", "WITH", "us.id = s.createdBy")
            ->innerJoin("Application\Entity\Customers", "cr", "WITH", "cr.id = s.customer")
            ->select('s')
            ->addSelect('cr.name as customername')
            ->addSelect('p.name, p.price, p.createdAt, p.image, p.barcode, p.CostPrice, p.sku, p.discountType, u.quantity, u.subtotal, u.createdAt, u.updatedAt, p.name, us.fullname, s.type, s.id, s.status, s.orderTax as TAX')
            ->addSelect("(u.subtotal + (u.subtotal * s.orderTax / 100) + s.shipping - (u.subtotal * s.discount / 100)) as grandTotal")  
            ->addSelect("(u.subtotal + s.shipping - (u.subtotal * s.discount / 100)) as HtTotal")  
            ->where("s.idcompany = :companyId")
            ->andWhere("s.type = 'BL'")
            ->setParameter('companyId', $companyId);

        $data = $query->getQuery()->getResult();

        // Initialize grand total sum
        $totalGrandTotal = 0;

        // Process the results to calculate the total grand total
        foreach ($data as $order) {
            // Adding grandTotal to the total sum
            $totalGrandTotal += $order['grandTotal'];
        }

        // Return data and grand total
        return [
            'orders' => $data,
            'totalGrandTotal' => $totalGrandTotal
        ];

    } catch (\Throwable $th) {
        throw $th;
        return [];
    }
}

}