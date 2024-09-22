<?php
namespace Application\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Authentication\AuthenticationService;
use Application\Service\SettingService;
use Application\Helper\TranslationHelper;

class AuthPlugin extends AbstractPlugin
{
    protected $authService;
    protected $SettingService;
    protected $translationHelper;

    public function __construct(
        AuthenticationService $authService,
        SettingService $SettingService,
        TranslationHelper $translationHelper // Inject TranslationHelper
    ) {
        $this->authService = $authService;
        $this->SettingService = $SettingService;
        $this->translationHelper = $translationHelper; // Initialize TranslationHelper
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

    public function hasCompany()
    {
        $user = $this->getUser();
        $userid = "";
        $company = "";

        if ($user) {
            $userid = (int) $user["id"];
            $company = $this->SettingService->getactifcompany($userid);
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($company)) {
                $_SESSION['companyName'] = $company[0]['companyName'];
            } else {
                $_SESSION['companyName'] = "";
            }
            return !empty($company);
        }
        return false;
    }

    public function Company()
    {
        $user = $this->getUser();
        $companies = "";
        if ($user) {
            $userid = $user["id"];
            $companies = $this->SettingService->getAllSettings($userid);
        }
        return $companies;
    }

    public function ActifCompany()
    {
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
        return $companies;
    }

    // New method for translations
    public function translate($key){
        if ($this->ActifCompany()) {  
            $actif = $this->ActifCompany();
            $lang = $actif[0]['language'];
            
            if ($lang == 'English') {
                $locale = 'origin';
            } elseif ($lang == 'French') {
                $locale = 'fr';
            } elseif ($lang == 'Arabe') {
                $locale = 'ar';
            } else {
                 $locale = 'origin';
            }
        } else {
            $locale = 'origin';
        }

        return $this->translationHelper->getTranslation($key, $locale);
    }

}