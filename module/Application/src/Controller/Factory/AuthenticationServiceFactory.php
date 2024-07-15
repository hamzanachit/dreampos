<?php
  

  namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;

class AuthenticationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get('Laminas\Db\Adapter\Adapter');
        $authAdapter = new CredentialTreatmentAdapter($dbAdapter, 'user', 'username', 'password', 'SHA1(?)');

        $authService = new AuthenticationService();
        $authService->setAdapter($authAdapter);

        return $authService;
    }
}