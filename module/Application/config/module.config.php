<?php

declare(strict_types=1);

namespace Application;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Application\Service\UserService;
use Application\Service\SettingService;
use Laminas\Session\Config\ConfigInterface;
use Laminas\Session\Service\SessionConfigFactory;
use Laminas\Session\SessionManager;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\RemoteAddr;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Container;
use Application\Service\DashboardService;
use Laminas\Authentication\AuthenticationService;
use Application\Listener\AuthListener;
use Doctrine\DBAL\Types\Type;
use Application\Doctrine\Type\EnumType;

use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Doctrine\ORM\Tools\EntityGenerator;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Laminas\Session\Config\SessionConfig;
 return [
    'service_manager' => [
        'factories' => [
            // Database Adapter (example)
            'Laminas\Db\Adapter\Adapter' => 'Laminas\Db\Adapter\AdapterServiceFactory',
             Laminas\Session\SessionManager::class => Application\Factory\SessionManagerFactory::class,


            // Session Config
            ConfigInterface::class => SessionConfigFactory::class,
            SessionManager::class => function ($container) {
                $config = $container->get(ConfigInterface::class);
                $sessionManager = new SessionManager($config);
                $sessionManager->setValidatorChain($container->get('ValidatorManager')->get('SessionValidatorChain'));
                return $sessionManager;
            },

            // Authentication Service
            AuthenticationService::class => Controller\Factory\AuthenticationServiceFactory::class,
            // Session Container
            Container::class => function ($container) {
                $manager = $container->get(SessionManager::class);
                return new Container('default', $manager);
            },

            // User Service (example)
            Service\UserService::class => function ($sm) {
                $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                return new Service\UserService($entityManager);
            },

            // Dashboard Service (example)
            DashboardService::class => function ($sm) {
                $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                return new DashboardService($entityManager);
            },

            'Application\Service\ProductService' => function ($sm) {
            $entityManager = $sm->get('doctrine.entitymanager.orm_default');
            return new \Application\Service\ProductService($entityManager);
        },
            //  SettingService::class => function($container) {
            //     // Retrieve EntityManager from Laminas Service Manager
            //     $entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
            //     return new \Application\Service\SettingService($entityManager);
            // },
        

               SettingService::class => function ($sm) {
                $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                return new SettingService($entityManager);
            },

        ],
    ],

    'controller_plugins' => [
        'aliases' => [
            'auth' => Controller\Plugin\AuthPlugin::class,
          'flashMessenger' => 'FlashMessenger', 

        ],
        'factories' => [
            Controller\Plugin\AuthPlugin::class => Controller\Plugin\AuthPluginFactory::class,
            'FlashMessenger' => \Laminas\Mvc\Controller\Plugin\FlashMessengerFactory::class, // Factory definition

        ],
    ],

 
    'session_config' => [
        'cookie_lifetime' => 60 * 60 * 1, // 1 hour
        'gc_maxlifetime'  => 60 * 60 * 24 * 30, // 30 days
    ],
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ],
    ],
    'session_storage' => [
       'type' => SessionArrayStorage::class,
    ],
    'session_validator' => [
        RemoteAddr::class,
        HttpUserAgent::class,
    ],
 
    

    'router' => [
        'routes' => [
            'users' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/users[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'index',
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],

            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'login',
                    ],
                ],
            ],

            'register' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/register',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'register',
                    ],
                ],
            ],

            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],

            // Dashboard
            'dashboard' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/dashboard',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
 
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            // product area
             'productsActions' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/products[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],
            // setting area
             'settingActions' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/settings[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\SettingController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],
               // setting area
             'categoryActions' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/category[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\SettingController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],
            // setting area
             'category__NOLAYOUT' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/ajaxcategory[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\AjaxSettingController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\DashboardController::class => Controller\Factory\DashboardControllerFactory::class,
            Controller\ProductController::class => Controller\Factory\ProductControllerFactory::class,
            Controller\SettingController::class => Controller\Factory\SettingControllerFactory::class,
            Controller\AjaxSettingController::class => Controller\Factory\AjaxSettingControllerFactory::class,

        ],
    ],

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];


$config = require 'config/autoload/local.php';

// Create a Doctrine ORM configuration
$doctrineConfig = Setup::createAnnotationMetadataConfiguration(
    [__DIR__ . '/module/Application/src/Entity'],
    true,
    null,
    null,
    false
);

// Create an EntityManager
$entityManager = EntityManager::create($config['doctrine']['connection']['orm_default']['params'], $doctrineConfig);

// Use the DisconnectedClassMetadataFactory to fetch metadata
$cmf = new DisconnectedClassMetadataFactory();
$cmf->setEntityManager($entityManager);

$metadata = $cmf->getAllMetadata();

if (empty($metadata)) {
    echo "No metadata found to generate entities.\n";
    exit(1);
}

// Generate entity classes from metadata
$generator = new EntityGenerator();
$generator->setUpdateEntityIfExists(true);
$generator->setGenerateStubMethods(true);
$generator->setGenerateAnnotations(true);

$generator->generate($metadata, __DIR__ . '/module/Application/src/Entity');

echo "Entity classes generated successfully.\n";