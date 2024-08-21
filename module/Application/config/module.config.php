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
            UserService::class => function ($sm) {
                $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                return new UserService($entityManager);
            },

            // Dashboard Service
            DashboardService::class => function ($sm) {
                $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                return new DashboardService($entityManager);
            },

            // Product Service
            'Application\Service\ProductService' => function ($sm) {
                $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                return new \Application\Service\ProductService($entityManager);
            },

            // Setting Service
            SettingService::class => function ($sm) {
                $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                return new SettingService($entityManager);
            },

            // SalesService Service
            SalesService::class => function ($sm) {
                $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                return new SalesService($entityManager);
            },

             // CustomersService Service
            CustomersService::class => function ($sm) {
                $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                return new CustomersService($entityManager);
            },


            //   Application\Service\CompanyService::class => function ($container) {
            //     $entityManager = $container->get('doctrine.entitymanager.orm_default');
            //     return new Application\Service\CompanyService($entityManager);
            // },

            AuthenticationMiddleware::class => function ($container) {
                return new AuthenticationMiddleware(
                    $container->get(AuthenticationService::class)
                );
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
            'FlashMessenger' => \Laminas\Mvc\Controller\Plugin\FlashMessengerFactory::class,
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

            // sales
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


            //  Customers
                    'customersActions' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/customers[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\CustomersController::class,
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