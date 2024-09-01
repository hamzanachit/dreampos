<?php
namespace Application\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Authentication\AuthenticationService;
use Application\Service\SettingService;

class AuthPlugin extends AbstractPlugin
{
    protected $authService;
    protected $SettingService;

    public function __construct(AuthenticationService $authService, SettingService $SettingService)
    {
        $this->authService = $authService;
        $this->SettingService = $SettingService;
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
   
        return $this->authService->getIdentity();
    }

    public function hasCompany(){

        $user = $this->getUser();
        $userid = "";
            // dd($user);
             $company = "";

        if ($user) {
            $userid = (int) $user["id"];  
            $company = $this->SettingService->getactifcompany($userid);    
            if (session_status() == PHP_SESSION_NONE) {     
                 session_start();
            }
             if(isset($company)){
                 $_SESSION['companyName'] = $company[0]['companyName'];
            }else{
                 $_SESSION['companyName'] = "";
            }
                 return !empty($company);
            }
             return false;
    }


     public function Company(){
         $user = $this->getUser();
          $companies = "";
        if ($user) {
            $userid = $user["id"];
            $companies = $this->SettingService->getAllSettings($userid);
         }
            // dd($companies);

        return $companies;
    }

     public function ActifCompany(){
        
         $user = $this->getUser();
          $companies = "";
        if ($user) {
             if (session_status() == PHP_SESSION_NONE) {
                      session_start();
             }

            $userid = $user["id"];
            $companies = $this->SettingService->getactifcompany($userid);

         }
            $_SESSION['company'] = $companies;
            // dd($companies ,  $userid  );

        return $companies;
    }
}