<?php
// src/Application/Module.php

namespace Application;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\SessionManager;
use Laminas\Session\Container;
use Laminas\Session\Config\SessionConfig;

class Module implements ConfigProviderInterface
{
// public function onBootstrap(MvcEvent $e)
// {
//     $serviceManager = $e->getApplication()->getServiceManager();
//     $sessionManager = $serviceManager->get(SessionManager::class);
    
//     // Check if the session manager is already started
//     // if (!$sessionManager->getId()) {
//     //     $sessionManager->start();
//     // }
    
//     Container::setDefaultManager($sessionManager);
// }



  public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        // Start the session
        $sessionManager = $serviceManager->get(SessionManager::class);
        $this->initializeSession($sessionManager);
        Container::setDefaultManager($sessionManager);

        // Get the Auth plugin from the ControllerPluginManager
        $authPlugin = $serviceManager->get('ControllerPluginManager')->get('auth');

        // Inject the Auth plugin into the global ViewModel
        $viewModel = $e->getViewModel();
        $viewModel->auth = $authPlugin;
        
    }
    public function getServiceConfig()
    {
        return [
            'factories' => [
                SessionManager::class => function ($container) {
                    $config = $container->get('config');
                    $sessionConfig = new SessionConfig();
                    $sessionConfig->setOptions($config['session_config']);
                    $sessionManager = new SessionManager($sessionConfig);
                    $sessionManager->start();
                    return $sessionManager;
                },
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    private function initializeSession(SessionManager $sessionManager)
    {
        try {
            $sessionManager->start();
            $container = new Container('initialized');
            if (!isset($container->init)) {
                $sessionManager->regenerateId(true);
                $container->init = 1;
            }
        } catch (\Laminas\Session\Exception\RuntimeException $ex) {
            error_log('Session validation failed: ' . $ex->getMessage());
            // Handle the exception, e.g., redirect to an error page, log the error, etc.
        }
    }
}