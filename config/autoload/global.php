<?php

use Doctrine\DBAL\Types\Type;
use Application\Doctrine\Type\EnumType;

/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * NOTE: This file is ignored from Git by default with the .gitignore included
 * in laminas-mvc-skeleton. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */
return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => [
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'erp',
                    'charset'  => 'utf8mb4', // Adjust charset if necessary
                ],
            ],
        ],
        'driver' => [
            'application_entities' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../../module/Application/src/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    'Application\Entity' => 'application_entities',
                ],
            ],
        ],
    ],
    'db' => [
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=erp;host=localhost',
        'username'       => 'root',
        'password'       => '',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ],
    ],
    'service_manager' => [
        'factories' => [
            Laminas\Db\Adapter\Adapter::class => Laminas\Db\Adapter\AdapterServiceFactory::class,
             'auth' => Application\Controller\Plugin\Factory\AuthPluginFactory::class,
             
        ],
    ],
    
    'controller_plugins' => [
        'factories' => [
            'AuthPlugin' => Application\Controller\Plugin\Factory\AuthPluginFactory::class,
        ],
    ],
     'session_config' => [
        'cookie_lifetime' => 60 * 60 * 24, // 1 hour
        'gc_maxlifetime' => 60 * 60 * 24 * 30, // 30 days
    ],
    'session_manager' => [
        'validators' => [
            \Laminas\Session\Validator\RemoteAddr::class,
            \Laminas\Session\Validator\HttpUserAgent::class,
        ],
    ],
    'session_storage' => [
        'type' => \Laminas\Session\Storage\SessionArrayStorage::class,
         'options' => [
        'name' => 'my_session',
    ],
    ],
    'session_containers' => [
        'UserSession',
    ],
];

// Register the custom enum type
Type::addType('enum', EnumType::class);