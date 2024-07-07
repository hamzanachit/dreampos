<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }


    
 public function layoutAction()
    {
        
       
        $viewModel = new ViewModel();
         $fullName = "";
        $auth = $this->plugin('auth');
        
        if ($auth->hasIdentity()) {
            $fullName = $auth->getIdentity();
            //  var_dump( $fullName);
            // Now $userId should contain the user ID
        }
       
        return new ViewModel([ 
            'userId' =>   $fullName,
        ]);
        $viewModel->setTemplate('application/dashboard/layout/layout');
        // C:\wamp64\www\erpp\module\Application\view\layout  

        return $viewModel;
    }
}