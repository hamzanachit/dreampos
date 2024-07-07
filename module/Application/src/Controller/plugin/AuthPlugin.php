<?php

namespace Application\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Authentication\AuthenticationService;
use Application\Entity\User; // Import your User entity

class AuthPlugin extends AbstractPlugin
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function getIdentity()
    {
         
        return $this->authService->getIdentity();
    }

    public function hasIdentity()
    {
        return $this->authService->hasIdentity();
    }

    public function getUser()
    {
         
        $identity = $this->authService->getIdentity(); 
        

        return $identity ;
    }
}