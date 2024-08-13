<?php

// module/Application/src/Controller/Factory/UserControllerFactory.php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\CustomersController;
use Application\Service\CustomersService;
use Laminas\ServiceManager\ServiceManager;


class CustomersControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $CustomersService = $container->get(CustomersService::class);
        return new CustomersController($CustomersService);
    }
}