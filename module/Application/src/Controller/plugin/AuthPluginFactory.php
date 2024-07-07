<?php

namespace Application\Controller\Plugin;

use Psr\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;

class AuthPluginFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $authService = $container->get(AuthenticationService::class);
        return new AuthPlugin($authService);
    }
}