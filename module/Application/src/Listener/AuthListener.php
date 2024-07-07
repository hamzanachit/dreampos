<?php

namespace Application\Listener;

use Laminas\Mvc\MvcEvent;
use Laminas\Authentication\AuthenticationService;

class AuthListener
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');

        // Define actions that do not require authentication
        $excludedActions = [
            'Application\Controller\AuthController' => ['login', 'register'],
        ];

        // Check if the action is excluded
        if (isset($excludedActions[$controller]) && in_array($action, $excludedActions[$controller])) {
            return;
        }

        // Check if user is authenticated
        if (!$this->authService->hasIdentity()) {
            $url = $e->getRouter()->assemble([], ['name' => 'login']);
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            return $response;
        }
    }
}