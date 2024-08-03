<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\SalesService; // Ensure this is imported correctly

class SalesController extends AbstractActionController
{
    private $salesservice;

    public function __construct(SalesService $salesservice)
    {
        $this->salesservice = $salesservice;
    }

    public function listAction()
    {
        $salesOrders = $this->salesservice->fetchAll();
        return new ViewModel(['salesOrders' => $salesOrders]);
    }

    public function addAction()
    {
        // Logic to handle adding a sales order
    }

    public function editAction()
    {
        // Logic to handle editing a sales order
    }

    public function deleteAction()
    {
        // Logic to handle deleting a sales order
    }
}