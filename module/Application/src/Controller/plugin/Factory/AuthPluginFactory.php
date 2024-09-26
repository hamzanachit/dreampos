<?php
 
 
namespace Application\Controller\Plugin\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Plugin\AuthPlugin;
use Laminas\Authentication\AuthenticationService;
use Application\Service\SettingService;
use Application\Helper\TranslationHelper;


class AuthPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        //    error_log("AuthPluginFactory invoked");
        $authService = $container->get(AuthenticationService::class);
        $SettingService = $container->get(SettingService::class);
        $TranslationHelper = $container->get(TranslationHelper::class);
        

        return new AuthPlugin($authService, $SettingService,$TranslationHelper);
    }
}