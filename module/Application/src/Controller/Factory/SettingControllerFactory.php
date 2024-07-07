<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\SettingController;
use Application\Service\SettingService;

class SettingControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
         $SettingService = $container->get(SettingService::class);
        return new SettingController($SettingService);
    }
}