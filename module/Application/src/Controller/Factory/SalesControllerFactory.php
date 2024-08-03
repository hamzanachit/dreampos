<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\SalesController;
use Application\Service\SalesService; // Ensure this is imported correctly

class SalesControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Inject dependencies and create SalesController instance
        $salesService = $container->get(SalesService::class);
        return new SalesController($salesService);
    }
}