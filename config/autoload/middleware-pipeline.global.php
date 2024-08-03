<?php
// In your configuration file, e.g., config/autoload/middleware-pipeline.global.php

return [
    'middleware_pipeline' => [
        'always' => [
            'middleware' => [
                \Application\Middleware\AuthenticationMiddleware::class,
            ],
            'priority' => 100,
        ],
    ],
];