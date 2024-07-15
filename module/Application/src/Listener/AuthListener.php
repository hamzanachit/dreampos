<?php
namespace Application\Listener;

use Laminas\Session\SessionManager;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\Authentication\AuthenticationService;

class SessionExpiryListener extends AbstractListenerAggregate
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(SessionManager::EVENT_EXPIRE, [$this, 'onSessionExpire'], $priority);
    }

    public function onSessionExpire($e)
    {
        $this->authService->clearIdentity();
    }
}