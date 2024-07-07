<?php

namespace Application\Controller\Factory;

use Application\Controller\AuthController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Session\SessionManager;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Retrieve dependencies from the container
        $dbAdapter = $container->get(Adapter::class);
        $authService = $container->get(AuthenticationService::class);
        $sessionManager = $container->get(SessionManager::class);

        // Instantiate the controller and inject dependencies
        return new AuthController($dbAdapter, $authService, $sessionManager);
    }
}