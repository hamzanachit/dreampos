<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\Order;
use Application\Entity\Setting;

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
            // Handle exceptions or log errors
            return 0;
        }
    }

    public function getTotalProducts($limit = 5)
    {
        try {
            $orderRepository = $this->entityManager->getRepository(Product::class);
            $latestOrders = $orderRepository->findBy([], ['createdAt' => 'DESC'], $limit);

            return $latestOrders;
        } catch (\Exception $e) {
            // Handle exceptions or log errors
            return [];
        }
    }





      public function getCheckCompanyById($userid){ 

    $setting = $this->entityManager->getRepository(Setting::class)->findOneBy(['creator' => $userid]);
    
    if ($setting === null) {
        return null; // Return null if no setting found for the email
    } else {
        return $setting; // Return the found Setting entity
    }
}






    // Add other dashboard-related methods as needed
}