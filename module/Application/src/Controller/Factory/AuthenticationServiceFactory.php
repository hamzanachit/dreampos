<?php

namespace Application\Controller\Factory;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session as SessionStorage;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Psr\Container\ContainerInterface;

class AuthenticationServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get('Laminas\Db\Adapter\Adapter');
        $authAdapter = new CallbackCheckAdapter($dbAdapter, 'users', 'username', 'password', function($hash, $password) {
            // Use password_verify or any other hash check
            return password_verify($password, $hash);
        });
        $authService = new AuthenticationService(new SessionStorage(), $authAdapter);
        return $authService;
    }
}