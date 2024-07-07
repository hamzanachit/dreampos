<?php

    namespace Application\Service; 

    use Doctrine\ORM\EntityManager;
    use Application\Entity\Setting;
    use Application\Entity\User;
    use Application\Entity\Category;

    class SettingService{
        
        protected $entityManager;

        public function __construct(EntityManager $entityManager){
            $this->entityManager = $entityManager;
        }


         public function getAllSettings($userid){
            try {
                $query = $this->entityManager->createQueryBuilder()
                        ->from('Application\Entity\Setting','s')
                        ->innerJoin("Application\Entity\User", "u","WITH", "u.id = s.creator")
                        ->select('s as setting,s.id,s.companyName,s.logo,s.language,s.skuFormat,s.color,s.darkMode,s.currency,s.country,s.companyCity,s.companyAddresse,s.companyPhone,s.companyEmail,s.companyStatus,s.creator,s.id as idcompany')
                        ->addselect('u.fullname')
                        ->Where("s.creator = ".$userid);
                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return [];
            }
        }


        
          public function CheckCompanyExist($idcompany,$userid){
            try {
                $query = $this->entityManager->createQueryBuilder()
                        ->from('Application\Entity\Setting','s') 
                        ->select('s.id,s.creator')
                        ->Where("s.creator = ".$userid)
                        ->AndWhere("s.id = ".$idcompany);
                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return null;
            }
        }

        public function AddSetting($CompanyName, $Logo, $Language, $SkuFormat, $Color, $DarkMode, $Currency,  $Country, $CompanyCity, $CompanyAddress, $CompanyPhone, $CompanyEmail, $CompanyStatus,$userid){
            $setting = new Setting();
            $setting->setCompanyName($CompanyName);
            $setting->setLogo($Logo);
            $setting->setLanguage($Language);
            $setting->setSkuFormat($SkuFormat);
            $setting->setColor($Color);
            $setting->setDarkMode($DarkMode);
            $setting->setCurrency($Currency);
            $setting->setCountry($Country);
            $setting->setCompanyCity($CompanyCity);
            $setting->setCompanyAddresse($CompanyAddress);
            $setting->setCompanyPhone($CompanyPhone);
            $setting->setCompanyEmail($CompanyEmail);
            $setting->setCompanyStatus($CompanyStatus);
            $setting->setCreator($userid);
            $this->entityManager->persist($setting);
            $this->entityManager->flush();
            return $setting;
        }



        //   public function editSetting($CompanyName, $Logo, $Language, $SkuFormat, $Color, $DarkMode, $Currency,  $Country, $CompanyCity, $CompanyAddress, $CompanyPhone, $CompanyEmail, $CompanyStatus,$userid){
        //     $setting = new Setting();
        //     $setting->setCompanyName($CompanyName);
        //     $setting->setLogo($Logo);
        //     $setting->setLanguage($Language);
        //     $setting->setSkuFormat($SkuFormat);
        //     $setting->setColor($Color);
        //     $setting->setDarkMode($DarkMode);
        //     $setting->setCurrency($Currency);
        //     $setting->setCountry($Country);
        //     $setting->setCompanyCity($CompanyCity);
        //     $setting->setCompanyAddresse($CompanyAddress);
        //     $setting->setCompanyPhone($CompanyPhone);
        //     $setting->setCompanyEmail($CompanyEmail);
        //     $setting->setCompanyStatus($CompanyStatus);
        //     $setting->setCreator($userid);
        //     $this->entityManager->persist($setting);
        //     $this->entityManager->flush();
        //     return $setting;
        // }





    public function editSetting($CompanyName, $Logo, $Language, $SkuFormat, $Color, $DarkMode, $Currency,  $Country, $CompanyCity, $CompanyAddress, $CompanyPhone, $CompanyEmail, $CompanyStatus,$userid,$idcompany){
        try {

            $setting = $this->entityManager->getRepository('Application\Entity\Setting')->find($idcompany);
            if (!$setting) {
                echo "setting not found";
            }
            $setting->setCompanyName($CompanyName);
            if($Logo != null){
            $setting->setLogo($Logo);

            }
            $setting->setLanguage($Language);
            $setting->setSkuFormat($SkuFormat);
            $setting->setColor($Color);
            $setting->setDarkMode($DarkMode);
            $setting->setCurrency($Currency);
            $setting->setCountry($Country);
            $setting->setCompanyCity($CompanyCity);
            $setting->setCompanyAddresse($CompanyAddress);
            $setting->setCompanyPhone($CompanyPhone);
            $setting->setCompanyEmail($CompanyEmail);
            $setting->setCompanyStatus($CompanyStatus);
            $setting->setCreator($userid);
            $this->entityManager->persist($setting);
            $this->entityManager->flush();

            return $setting;
        } catch (\Throwable $th) {

            throw $th;
        }
    }


      public function getAllCategorys(){
            try {
                $query = $this->entityManager->createQueryBuilder()
                        ->from('Application\Entity\Category','c') 
                        ->select('c as category,c.categoryName,c.createdBy,c.description,c.categoryCode,c.id ');
                        // ->Where("s.creator = ".$userid)
                        // ->AndWhere("s.id = ".$idcompany);
                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return 'khaaaaaaaaawi';
            }
        }


        
    
     
    public function addCategory($categoryName, $description, $categoryCode, $Logo,$userid){
            $Category = new Category();
            $Category->setCategoryName($categoryName);
            // $setting->setLogo($Logo);
            $Category->setDescription($description);
            $Category->setCategoryCode($categoryCode);
            $Category->setCreatedBy($userid);
            $this->entityManager->persist($Category);
            $this->entityManager->flush();
            return $Category;
        }

}