<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\AjaxSettingController;
use Application\Service\SettingService;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;

class AjaxSettingControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
         $SettingService = $container->get(SettingService::class);
        return new AjaxSettingController($SettingService);
    }
}