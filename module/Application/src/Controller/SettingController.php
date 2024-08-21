<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\SettingService;
use Laminas\Session\Container;


class SettingController extends AbstractActionController{
         protected $settingService;

    public function __construct(SettingService $settingService){
        $this->settingService = $settingService;
    }

      public function addcompanyinfoAction(){
         $auth = $this->plugin('auth');
         $user = $auth->getUser();
        $userid = $user['id'] ;
        $creator = $user['fullName'] ;
        $companyinfo = $this->settingService->getAllSettings($userid);
        $userId = 2;
        $idcompany  ="";
        $oldcompanyname = $companyinfo[0]["companyName"];
        $getCompaniesByUserId = $this->settingService->getCompaniesByUserId($userId);
        // jdbc:mysql://188.42.42.14:3306/admin_montreal?noAccessToProcedureBodies=true
         if ($this->getRequest()->isPost()) {
            $postdata = $this->params()->fromPost();
            $files = $this->getRequest()->getFiles();
            $CompanyName = htmlspecialchars($postdata['CompanyName'] ?? '');
            $Logo = '';
            $Language = htmlspecialchars($postdata['Language'] ?? '');
            $SkuFormat = htmlspecialchars($postdata['SkuFormat'] ?? '');
            $ICE = htmlspecialchars($postdata['ICE'] ?? '');
            $DarkMode = htmlspecialchars($postdata['DarkMode'] ?? '');
            $Currency = htmlspecialchars($postdata['Currency'] ?? '');
            $Country = htmlspecialchars($postdata['Country'] ?? '');
            $CompanyCity = htmlspecialchars($postdata['CompanyCity'] ?? '');
            $CompanyAddress = htmlspecialchars($postdata['CompanyAddress'] ?? '');
            $CompanyPhone = htmlspecialchars($postdata['CompanyPhone'] ?? '');
            $CompanyEmail = htmlspecialchars($postdata['CompanyEmail'] ?? '');
            $CompanyStatus = htmlspecialchars($postdata['CompanyStatus'] ?? '');
            $blformat = htmlspecialchars($postdata['blformat'] ?? '');
            $invoiceformat = htmlspecialchars($postdata['invoiceformat'] ?? '');


            $codepostal = htmlspecialchars($postdata['codepostal'] ?? '');
            $CEO = htmlspecialchars($postdata['CEO'] ?? '');
            $cnss = htmlspecialchars($postdata['cnss'] ?? '');
            $Patent = htmlspecialchars($postdata['Patent'] ?? '');
            $RC = htmlspecialchars($postdata['RC'] ?? '');
            $NIF = htmlspecialchars($postdata['IF'] ?? '');
            $legalEntityType = htmlspecialchars($postdata['legalEntityType'] ?? '');

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
                    return $this->redirect()->toRoute('dashboard', ['action' => 'list']);
                }
            }
            $CheckCompanyExist = $this->settingService->CheckCompanyExist($CompanyName,$userid);
            // dd( $CheckCompanyExist);
            if(empty($CheckCompanyExist)){  
                                
                  $resultAdd = $this->settingService->AddSetting($CompanyName,$Logo,$Language, $SkuFormat, $ICE, $DarkMode, $Currency,$Country, $CompanyCity, $CompanyAddress, $CompanyPhone, $CompanyEmail, $CompanyStatus,$userid,$blformat ,$invoiceformat,$legalEntityType, $NIF, $RC , $Patent,$cnss, $CEO, $codepostal );
                if ($resultAdd != null){
                    $resultAdd = $this->settingService->AddCompany($CompanyName,$userid );
                }
                  return $this->redirect()->toRoute('dashboard', ['action' => 'list']);


            }else{
                  echo '<script type="text/javascript">';
                    echo 'alert("Company with this name already exist");';
                    echo '</script>'; 
             
            }
            // return $this->redirect()->toRoute('/dashboard');
        }
           return new ViewModel([
            'companyinfo' => $companyinfo,
            'idcompany' => $idcompany,
            'creator' => $creator
        ]);

            $viewModel->setTemplate('application/setting/add'); 
            return $viewModel;
    }







    public function editAction(){
         $auth = $this->plugin('auth');
         $user = $auth->getUser();
        $userid =$user['id'];
        $companyinfo = $this->settingService->getactifcompany($userid);
        // dd( $companyinfo);
        if ($companyinfo == null){
                   return $this->redirect()->toRoute('settingActions', ['action' => 'addcompanyinfo']);
            }
        if (isset($companyinfo[0]['idcompany'])){
            
          $idcompany = $companyinfo[0]['idcompany'];

        }
        
         if ($this->getRequest()->isPost()) {
            $postdata = $this->params()->fromPost();
            $files = $this->getRequest()->getFiles();
            $CompanyName = htmlspecialchars($postdata['CompanyName'] ?? '');
            $Logo = '';
            $Language = htmlspecialchars($postdata['Language'] ?? '');
            $SkuFormat = htmlspecialchars($postdata['SkuFormat'] ?? '');
            $ICE = htmlspecialchars($postdata['ICE'] ?? '');
            $DarkMode = htmlspecialchars($postdata['DarkMode'] ?? '');
            $Currency = htmlspecialchars($postdata['Currency'] ?? '');
            $Country = htmlspecialchars($postdata['Country'] ?? '');
            $CompanyCity = htmlspecialchars($postdata['CompanyCity'] ?? '');
            $CompanyAddress = htmlspecialchars($postdata['CompanyAddress'] ?? '');
            $CompanyPhone = htmlspecialchars($postdata['CompanyPhone'] ?? '');
            $CompanyEmail = htmlspecialchars($postdata['CompanyEmail'] ?? '');
            $blformat = htmlspecialchars($postdata['blformat'] ?? '');
            $invoiceformat = htmlspecialchars($postdata['invoiceformat'] ?? '');
            $codepostal = htmlspecialchars($postdata['codepostal'] ?? '');
            $CEO = htmlspecialchars($postdata['CEO'] ?? '');
            $cnss = htmlspecialchars($postdata['cnss'] ?? '');
            $Patent = htmlspecialchars($postdata['Patent'] ?? '');
            $RC = htmlspecialchars($postdata['RC'] ?? '');
            $NIF = htmlspecialchars($postdata['IF'] ?? '');
            $legalEntityType = htmlspecialchars($postdata['legalEntityType'] ?? '');

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
                    return $this->redirect()->toRoute('settingActions', ['action' => 'edit']);
                }
            } 



              $CheckCompanyExist = $this->settingService->CheckCompanyExist($CompanyName,$userid);
            // dd( $CheckCompanyExist);
            if(empty($CheckCompanyExist)){ 

                $resultEdit = $this->settingService->editSetting($CompanyName,$Logo,$Language, $SkuFormat, $ICE, $DarkMode, $Currency,$Country, $CompanyCity, $CompanyAddress, $CompanyPhone, $CompanyEmail, $userid,$idcompany,$blformat ,$invoiceformat ,$legalEntityType, $NIF, $RC , $Patent,$cnss, $CEO, $codepostal );
                
                  if($resultEdit != null){
                         $resulteditcompany = $this->settingService->editCompany($CompanyName,$userid,$idcompany);
                 return $this->redirect()->toRoute('settingActions', ['action' => 'edit']);

                    }
              } else{
                  echo '<script type="text/javascript">';
                    echo 'alert("Company with this name already exist");';
                    echo '</script>'; 
             
                }
        }

 
          return new ViewModel([
            'companyinfo' => $companyinfo,
        ]);

            $viewModel->setTemplate('application/setting/edit'); 
            return $viewModel;
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


    //section sub category
    public function subcategoryAction() {
        $AllSubCategorys = '';
        
        $Categorys = $this->settingService->getAllCategorys();
        $AllSubCategorys = $this->settingService->getAllSubCategorys();
        if ($AllSubCategorys === null ){
            $AllSubCategorys = '';
        }
        // dd(getAllSubCategorys);
        $viewModel = new ViewModel([
            'AllSubCategorys' =>$AllSubCategorys,
            'Categorys' =>$Categorys,
        ]);

    $viewModel->setTemplate('application/subcategory/list');  

    return $viewModel;
}

 //section brands
    public function brandAction() {
        $AllSubCategorys = '';
        
        $Categorys = $this->settingService->getAllCategorys();
        $AllSubCategorys = $this->settingService->getAllSubCategorys();
        if ($AllSubCategorys === null ){
            $AllSubCategorys = '';
        }
        // dd(getAllSubCategorys);
        $viewModel = new ViewModel([
            'AllSubCategorys' =>$AllSubCategorys,
            'Categorys' =>$Categorys,
        ]);

    $viewModel->setTemplate('application/subcategory/list');  

    return $viewModel;
}


    public function selectcompanyAction() {
        $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $userid =$user['id'];
        $getAllcompany = '';
        $getAllcompany = $this->settingService->getAllSettings($userid);
        // dd(  $getAllcompany);
        if ($getAllcompany === null ){
            $getAllcompany = '';
        }
        $viewModel = new ViewModel([
            'getAllcompany' =>$getAllcompany,
        ]);

        $viewModel->setTemplate('application/setting/selectCompany');  
        return $viewModel;
    }



    
    public function ChangecompanyAction(){
         $auth = $this->plugin('auth');
         $user = $auth->getUser();
         $userid =$user['id'];
     
        
         if ($this->getRequest()->isPost()) {
            $postdata = $this->params()->fromPost();
             $idcompany = htmlspecialchars($postdata['idcompany'] ?? '');
    
         
            // dd(  $idcompany );
                $resultEdit = $this->settingService->Changecompany($userid,$idcompany);
                
             
        }
         return $this->redirect()->toRoute('settingActions', ['action' => 'edit']);

    }

   public function invoiceAction(){
         $auth = $this->plugin('auth');
         $user = $auth->getUser();
         $ActifCompany = $auth->ActifCompany();
         $userid =$user['id'];
     
            // dd(  $ActifCompany[0]['idcompany']);
          $idcompany = $ActifCompany[0]['idcompany'];
          
        //  if ($this->getRequest()->isPost()) {
        //     $postdata = $this->params()->fromPost();
        //      $idcompany = htmlspecialchars($postdata['idcompany'] ?? '');
    
         
        //     // dd(  $ActifCompany );
        //         $resultEdit = $this->settingService->Changecompany($userid,$idcompany);
                
             
        // }
        //  return $this->redirect()->toRoute('settingActions', ['action' => 'invoice']);

    
         $viewModel = new ViewModel([
            // 'getAllcompany' =>$resultEdit,
        ]);

        // $viewModel->setTemplate('application/setting/in');  
        // return $viewModel;
    }

}