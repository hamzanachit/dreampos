<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;
use Application\Service\DashboardService;
use Application\Entity\User;
use Application\Entity\Setting;

class DashboardController extends AbstractActionController
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        
    }

    public function indexAction(){
    $mail = "";
    $auth = $this->plugin('auth');
    if ($auth->hasIdentity()){
            
            $mail = $auth->getIdentity();
            $user = $auth->getUser();
            $userid =$user['id'];

            // dd(  $userid );
            $checkcompany = $this->dashboardService->getCheckCompanyById($userid);
        if ($checkcompany === null) {
                echo '<script>alert("Company check returned null.");</script>';
                echo '<script>window.location.href = "' . $this->url()->fromRoute('settingActions', ['action' => 'edit']) . '";</script>';
                exit;
            }
        }

        $viewModel = new ViewModel();

        return new ViewModel([ 
            'userId' =>   $mail,
        ]);

        $viewModel->setTemplate('application/dashboard/index');  

        return $viewModel;
    }




 
}