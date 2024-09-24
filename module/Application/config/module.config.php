<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Authentication\AuthenticationService;
use Application\Service\UserService;
use Application\Service\SettingService;
use Application\Service\DashboardService;
use Application\Service\SalesService;
use Application\Service\CustomersService;
use Application\Middleware\AuthenticationMiddleware;
use Laminas\Authentication\Storage\Session as SessionStorage;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Application\Helper\TranslationHelper;

return [
    'service_manager' => [
        'factories' => [
            // Authentication Service
            AuthenticationService::class => function ($container) {
                $dbAdapter = $container->get(Adapter::class);
                $authService = new AuthenticationService();
                $authService->setStorage(new SessionStorage('user_session'));
                $authService->setAdapter(new CallbackCheckAdapter(
                    $dbAdapter,
                    'user',
                    'email',
                    'password',
                    function ($hash, $password) {
                        return password_verify($password, $hash);
                    }
                ));
                return $authService;
            },

            // User Service
            UserService::class => function ($container) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                return new UserService($entityManager);
            },

            // Dashboard Service
            DashboardService::class => function ($container) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                return new DashboardService($entityManager);
            },

            // Product Service
            'Application\Service\ProductService' => function ($container) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                return new \Application\Service\ProductService($entityManager);
            },

            // Setting Service
            SettingService::class => function ($container) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                return new SettingService($entityManager);
            },

            // SalesService Service
            SalesService::class => function ($container) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                return new SalesService($entityManager);
            },

            // CustomersService Service
            CustomersService::class => function ($container) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                return new CustomersService($entityManager);
            },

            // DbAdapter
            Adapter::class => 'Laminas\Db\Adapter\AdapterServiceFactory',
            'DbAdapter' => function($container) {
                $config = $container->get('config');
                return new Adapter($config['db']); // Assuming you have a 'db' config section
            },
            // TranslationHelper
            TranslationHelper::class => function ($container) {
                return new TranslationHelper(
                    $container->get(Adapter::class)
                );
            },

            // AuthPlugin
            Controller\Plugin\AuthPlugin::class => function ($container) {
                return new \Application\Controller\Plugin\AuthPlugin(
                    $container->get(AuthenticationService::class),
                    $container->get(SettingService::class),
                    $container->get(TranslationHelper::class) // Inject TranslationHelper
                );
            },

            // AuthenticationMiddleware
            AuthenticationMiddleware::class => function ($container) {
                return new AuthenticationMiddleware(
                    $container->get(AuthenticationService::class)
                );
            },


         
        ],
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
                    'route' => '/register',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'register',
                    ],
                ],
            ],

            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'logout',
                    ],
                ],
            ],

            'dashboard' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/dashboard',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action' => 'index',
                    ],
                ],
            ],

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

            'ajaxproduct' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/ajaxproduct[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],

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
            'langueActions' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/langue[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\SettingController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],

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

            'salesActions' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/sales[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\SalesController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],

            'salesActions__NOLAYOUT' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/salesajax[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\SalesController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],

            'invoiceActions' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/invoices[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\InvoiceController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],

            'partnerCapital' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/partnercapital[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\PartnerCapitalController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],

            'subfolderActions' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/subfolder[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\SubfolderController::class,
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],

            'productOrder' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/productorder[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\ProductOrderController::class,
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
            Controller\SalesController::class => Controller\Factory\SalesControllerFactory::class,
            Controller\CustomersController::class => Controller\Factory\CustomersControllerFactory::class,
            
        ],
    ],


    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            [
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
            ],
        ],
    ],
   'controller_plugins' => [
        'aliases' => [
            'auth' => Controller\Plugin\AuthPlugin::class,
            'flashMessenger' => 'FlashMessenger',
        ],
        'factories' => [
            Controller\Plugin\AuthPlugin::class => Controller\Plugin\AuthPluginFactory::class,
            'FlashMessenger' => \Laminas\Mvc\Controller\Plugin\FlashMessengerFactory::class,
             'MvcTranslator' => Laminas\I18n\Translator\TranslatorServiceFactory::class,

        ],
    ],
    
    'middleware_pipeline' => [
    'always' => [
        [
            'middleware' => AuthenticationMiddleware::class,
            'priority' => 100,
        ],
     ],
    ],
     'view_helpers' => [
        'factories' => [
            TranslationHelper::class => InvokableFactory::class,
            'translation' => function($container) {
                    $dbAdapter = $container->get('DbAdapter'); // Get your DB adapter from the service manager
                    return new  TranslationHelper($dbAdapter);
            },
        ],
        ],
    
      'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
   
];