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
                // dd($auth->ActifCompany());
                $company = $auth->ActifCompany();
                $idcompany = $company[0]['id'];
                $currency = $company[0]['currency'];
                $checkcompany = $this->dashboardService->getCheckCompanyById($userid);



          







                if ($checkcompany === null) {
                        echo '<script>alert("Company check returned that this user do not has any company.");</script>';
                        echo '<script>window.location.href = "' . $this->url()->fromRoute('settingActions', ['action' => 'addcompanyinfo']) . '";</script>';
                        exit;
                } 

                $TotalOrders = $this->dashboardService->getAllProducts( $idcompany);
                $OrdersByCompany = $this->dashboardService->getOrdersByCompany( $idcompany);  
                // dd($OrdersByCompany );

        }
        $viewModel = new ViewModel();
        return new ViewModel([ 
            'userId' =>   $mail,
            'TotalOrders' =>   $TotalOrders,
            'OrdersByCompany' =>   $OrdersByCompany,
            'currency' =>   $currency,
        ]);

        $viewModel->setTemplate('application/dashboard/index');  

        return $viewModel;
    }


   public function DashboardAction(){
    $mail = "";
    $auth = $this->plugin('auth');
    if ($auth->hasIdentity()){
            $mail = $auth->getIdentity();
            $user = $auth->getUser();
            $userid =$user['id'];
            $checkcompany = $this->dashboardService->getCheckCompanyById($userid);
       
        }

        $viewModel = new ViewModel();

        return new ViewModel([ 
            'userId' =>   $mail,
        ]);

        $viewModel->setTemplate('application/dashboard/dashboard');  

        return $viewModel;
    }




 
}