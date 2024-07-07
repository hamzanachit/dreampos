<?php

// module/Application/src/Controller/Factory/UserControllerFactory.php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\UserController;
use Application\Service\UserService;
 use Laminas\ServiceManager\ServiceManager;


class UserControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userService = $container->get(UserService::class);
        return new UserController($userService);
    }
}
