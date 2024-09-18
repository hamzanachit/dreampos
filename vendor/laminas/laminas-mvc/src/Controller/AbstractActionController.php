<?php

namespace Laminas\Mvc\Controller;

use Laminas\Mvc\Exception;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;


/**
 * Basic action controller
 */
abstract class AbstractActionController extends AbstractController
{
    /**
     * {@inheritDoc}
     */
    protected $eventIdentifier = __CLASS__;

    /**
     * Default action if none provided
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'content' => 'Placeholder page'
        ]);
    }

    /**
     * Action called if matched action does not exist
     *
     * @return ViewModel
     */
    public function notFoundAction()
    {
        $event      = $this->getEvent();
        $routeMatch = $event->getRouteMatch();
        $routeMatch->setParam('action', 'not-found');

        $helper = $this->plugin('createHttpNotFoundModel');
        return $helper($event->getResponse());
    }

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException
     */
 
    public function onDispatch(MvcEvent $e) {
    $authService = $this->plugin('auth');
    $routeMatch = $e->getRouteMatch();
    $currentRoute = $routeMatch ? $routeMatch->getMatchedRouteName() : null;
    // dd($_SESSION['company']);

         
    // Authentication check
    if (!$authService->hasIdentity()){
        if ($currentRoute !== 'login' && $currentRoute !== 'register') {
            return $this->redirect()->toRoute('login');
        }
        
    } else if (!$authService->hasCompany()) {
        $currentAction = $routeMatch ? $routeMatch->getParam('action') : null;
        if ($currentRoute !== 'settingActions' || $currentAction !== 'addcompanyinfo') {
            return $this->redirect()->toRoute('settingActions', ['action' => 'addcompanyinfo']);
        }
    } 
     $company ="";
        $user = $authService->getUser();
        if(!empty(  $user )){
        $userid = $user['id'];
        // dd( $user['id']);
        $company = $authService->ActifCompany();
        // dd( $company['companyStatus']);
}
        $hasActiveCompany = false;
        if (!empty($company)){
        foreach ($company as $row) {
            $Companystatus = $row['companyStatus'];

            if ($Companystatus === 'actif') {
                $hasActiveCompany = true;
                break;
            }
        } 
          
        if ($hasActiveCompany == false) {
             if ($currentRoute !== 'settingActions') {
                 return $this->redirect()->toRoute('settingActions', ['action' => 'selectcompany']);

            }
        }

    }

    if (!$routeMatch) {
        throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
    }

    $action = $routeMatch->getParam('action', 'not-found');
    $method = static::getMethodFromAction($action);

    if (!method_exists($this, $method)) {
        $method = 'notFoundAction';
    }

    $actionResponse = $this->$method();
    $e->setResult($actionResponse);

    return $actionResponse;
}


 

}