<?php  
namespace Application\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Laminas\Session\Config\SessionConfig;
use Laminas\Session\SessionManager;
use Laminas\Session\Storage\SessionArrayStorage;

class SessionManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config['session_config']);

        $sessionManager = new SessionManager($sessionConfig, new SessionArrayStorage());
        $sessionManager->start();

        return $sessionManager;
    }
}