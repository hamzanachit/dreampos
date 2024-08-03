<?php

// namespace Application\Controller\Plugin;

// use Psr\Container\ContainerInterface;
// use Laminas\Authentication\AuthenticationService;

// class AuthPluginFactory
// {
//     public function __invoke(ContainerInterface $container)
//     {
//         $authService = $container->get(AuthenticationService::class);
//         return new AuthPlugin($authService);
//     }
// }

 
namespace Application\Controller\Plugin;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Plugin\AuthPlugin;
use Laminas\Authentication\AuthenticationService;
use Application\Service\SettingService;

class AuthPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authService = $container->get(AuthenticationService::class);
        $SettingService = $container->get(SettingService::class);

        return new AuthPlugin($authService, $SettingService);
    }
}