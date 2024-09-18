<?php

// module/Application/src/Controller/Factory/UserControllerFactory.php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\DashboardController;
use Application\Service\DashboardService;
use Laminas\ServiceManager\ServiceManager;



class DashboardControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $DashboardService = $container->get(DashboardService::class);
        return new DashboardController($DashboardService);
    }
}