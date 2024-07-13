<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\SettingService;

class SettingController extends AbstractActionController{
         protected $settingService;

    public function __construct(SettingService $settingService){
        $this->settingService = $settingService;
    }

    public function editAction(){
         $auth = $this->plugin('auth');
         $user = $auth->getUser();
        $userid =$user['id'];
        $companyinfo = $this->settingService->getAllSettings($userid);
          $idcompany = $companyinfo[0]['idcompany'];
         if ($this->getRequest()->isPost()) {
            $postdata = $this->params()->fromPost();
            $files = $this->getRequest()->getFiles();
             $CompanyName = htmlspecialchars($postdata['CompanyName'] ?? '');
            $Logo = '';
            $Language = htmlspecialchars($postdata['Language'] ?? '');
            $SkuFormat = htmlspecialchars($postdata['SkuFormat'] ?? '');
            $Color = htmlspecialchars($postdata['Color'] ?? '');
            $DarkMode = htmlspecialchars($postdata['DarkMode'] ?? '');
            $Currency = htmlspecialchars($postdata['Currency'] ?? '');
            $Country = htmlspecialchars($postdata['Country'] ?? '');
            $CompanyCity = htmlspecialchars($postdata['CompanyCity'] ?? '');
            $CompanyAddress = htmlspecialchars($postdata['CompanyAddress'] ?? '');
            $CompanyPhone = htmlspecialchars($postdata['CompanyPhone'] ?? '');
            $CompanyEmail = htmlspecialchars($postdata['CompanyEmail'] ?? '');
            $CompanyStatus = htmlspecialchars($postdata['CompanyStatus'] ?? '');
            $uploadPath = 'public/img/logo/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            if (!empty($files['Logo']['name'])) {
                $Logo = basename($files['Logo']['name']);
                $tempPath = $files['Logo']['tmp_name'];
                $uploadFile = $uploadPath . DIRECTORY_SEPARATOR . $Logo;
                if (move_uploaded_file($tempPath, $uploadFile)) {
                    } else { 
                    echo 'Failed to upload logo file.';
                    return $this->redirect()->toRoute('settings', ['action' => 'add']);
                }
            }
            
             $CheckCompanyExist = $this->settingService->CheckCompanyExist($idcompany,$userid );
            if ($CheckCompanyExist === null ){
                
                    $resultAdd = $this->settingService->AddSetting($CompanyName,$Logo,$Language, $SkuFormat, $Color, $DarkMode, $Currency,$Country, $CompanyCity, $CompanyAddress, $CompanyPhone, $CompanyEmail, $CompanyStatus,$userid );

            }else{
            
                $resultAdd = $this->settingService->editSetting($CompanyName,$Logo,$Language, $SkuFormat, $Color, $DarkMode, $Currency,$Country, $CompanyCity, $CompanyAddress, $CompanyPhone, $CompanyEmail, $CompanyStatus,$userid,$idcompany );

                
            }
                 return $this->redirect()->toRoute('settingActions', ['action' => 'edit']);
        }
 
          return new ViewModel([
            'companyinfo' => $companyinfo,
        ]);
    }






    public function listAction() {
    $AllCategorys = '';
    $AllCategorys = $this->settingService->getAllCategorys();
    
    if ($AllCategorys === null ){
        $AllCategorys = '';
    }


   
    
    $viewModel = new ViewModel([

        'AllCategorys' =>$AllCategorys,
        
    ]);

    $viewModel->setTemplate('application/category/list');  

    return $viewModel;
}





}