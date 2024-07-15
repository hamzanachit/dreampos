<?php

// module/Application/src/Controller/BaseController.php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class BaseController extends AbstractActionController
{
    protected function checkAuthentication()
    {
        $authService = $this->getServiceLocator()->get(AuthenticationService::class);

        if (!$authService->hasIdentity()) {
            return $this->redirect()->toRoute('login');
        }
    }
}