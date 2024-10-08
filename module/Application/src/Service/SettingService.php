<?php

    namespace Application\Service; 

    use Doctrine\ORM\EntityManager;
    use Application\Entity\Setting;
    use Application\Entity\User;
    use Application\Entity\Category;
    use Application\Entity\SubCategory;
    use Application\Entity\Company; 
    use Application\Entity\Translations; 

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
                        ->select('s as setting,s.id,s.companyName,s.logo,s.language,s.skuFormat,s.ICE,s.darkMode,s.currency,s.country,s.companyCity,s.companyAddresse,s.companyPhone,s.companyEmail,s.companyStatus,s.creator,s.id as idcompany')
                        ->addselect('u.fullname')
                        ->Where("s.creator = ".$userid);
                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return [];
            }
    }


    //  public function getactifcompany($userid){
    //         try {
    //             $query = $this->entityManager->createQueryBuilder()
    //                     ->from('Application\Entity\Setting','s')
    //                     ->innerJoin("Application\Entity\User", "u","WITH", "u.id = s.creator")
    //                     ->select('s as setting,s.id,s.companyName,s.logo,s.language,s.skuFormat,s.ICE,s.darkMode,s.currency,s.country,s.companyCity,s.companyAddresse,s.companyPhone,s.companyEmail,s.companyStatus,s.creator,s.id as idcompany,s.invoiceformat,s.blformat,s.NIF,s.RC,s.Patent,s.cnss,s.CEO,s.codepostal,s.Esformat,s.Tax')
    //                     ->addselect('u.fullname')
    //                     ->Where("s.creator = ".$userid)
    //                     ->Where("s.companyStatus = 'actif'");
    //             $data = $query->getQuery()->getResult();
    //             return $data;
    //         } catch (\Throwable $th) {
    //             throw $th;
    //             return [];
    //         }
    // }

    public function getactifcompany($userid) {
    try {
        $query = $this->entityManager->createQueryBuilder() 
            ->select('s as setting,s.id,s.companyName,s.logo,s.language,s.skuFormat,s.ICE,s.darkMode,s.currency,s.country,s.companyCity,s.companyAddresse,s.companyPhone,s.companyEmail,s.companyStatus,s.creator,s.id as idcompany,s.invoiceformat,s.blformat,s.NIF,s.RC,s.Patent,s.cnss,s.CEO,s.codepostal,s.Esformat,s.Tax')
            ->from('Application\Entity\Setting', 's')
            ->innerJoin('Application\Entity\User', 'u', 'WITH', 'u.id = s.creator')
            ->addselect('u.fullname')
            ->where('s.creator = :userid')
            ->andWhere('s.companyStatus = :status')
            ->setParameter('userid', $userid)
            ->setParameter('status', 'actif');

        $data = $query->getQuery()->getResult();
        return $data;
        // dd($data);
    } catch (\Throwable $th) {
        // Log the exception or handle it as needed
        // Example: error_log($th->getMessage());
        throw $th; // Re-throw exception after logging
        return []; // Return an empty array in case of an exception
    }
}


public function ChangeCompany($userId, $idCompany){
    try {
        // Find the company by its ID
        $company = $this->entityManager->getRepository('Application\Entity\Setting')->find($idCompany);
        
        if ($company === null) {
            throw new \Exception("Company with ID $idCompany not found");
        }
         $companies = $this->entityManager->getRepository('Application\Entity\Setting')->findAll();
        foreach ($companies as $company) {
            if ($company->getId() == $idCompany) {
                // dd($company->getId(), $idCompany);
                $company->setCompanyStatus("actif");
                $this->entityManager->persist($company);
            }else{
                // dd($company->getId(), $idCompany);

                 $company->setCompanyStatus("desable");
                $this->entityManager->persist($company);
            }
        }
        // Flush changes to the database
        $this->entityManager->flush();
        return $company;
    } catch (\Throwable $th) {
        error_log($th->getMessage());
        throw $th;
    }
}




   public function getCompaniesByUserId($userId) {
    try {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('s')
            ->from('Application\Entity\Setting', 's')
            ->where('s.creator = :userId')
            ->setParameter('userId', $userId);
            $query = $queryBuilder->getQuery();
            $data = $query->getResult();
            // dd($data);
           return $data;
            } catch (\Throwable $th) { 
                throw $th;
            }
        }


        

     public function CheckCompanyExistadd($CompanyName, $userid){
        $query = $this->entityManager->createQueryBuilder()
            ->from('Application\Entity\Setting', 's')
            ->select('s.id, s.creator, s.companyName')
            ->where('s.companyName = :companyName')
            ->Andwhere('s.creator = :creator')
            ->setParameter('companyName', $CompanyName)
            ->setParameter('creator', $userid)
            ->getQuery();
        $data = $query->getResult();
        return $data;
     } 

     public function CheckCompanyExist($CompanyName, $userid,$idcompany){
        $query = $this->entityManager->createQueryBuilder()
            ->from('Application\Entity\Setting', 's')
            ->select('s.id, s.creator, s.companyName')
            ->where('s.companyName = :companyName')
            ->Andwhere('s.creator = :creator')
            ->Andwhere('s.id = :id')
            ->setParameter('companyName', $CompanyName)
            ->setParameter('id', $idcompany)
            ->setParameter('creator', $userid)
            ->getQuery();
        $data = $query->getResult();
        return $data;
   
    }


        public function AddSetting($CompanyName,$Logo,$Language, $SkuFormat, $ICE, $DarkMode, $Currency,$Country, $CompanyCity, $CompanyAddress, $CompanyPhone, $CompanyEmail, $userid,$idcompany,$blformat ,$invoiceformat ,$legalEntityType, $NIF, $RC , $Patent,$cnss, $CEO, $codepostal, $TAX,$Esformat,$CompanyStatus ){
             $setting = new Setting();
            $setting->setCompanyName($CompanyName);
            $setting->setLogo($Logo);
            $setting->setLanguage($Language);
            $setting->setSkuFormat($SkuFormat);
            $setting->setICE($ICE);
            $setting->setDarkMode($DarkMode);
            $setting->setCurrency($Currency);
            $setting->setCountry($Country);
            $setting->setCompanyCity($CompanyCity);
            $setting->setCompanyAddresse($CompanyAddress);
            $setting->setCompanyPhone($CompanyPhone);
            $setting->setCompanyEmail($CompanyEmail);
            $setting->setCompanyStatus($CompanyStatus);
            $setting->setCreator($userid);
            $setting->setBlformat($blformat);
            $setting->setInvoiceformat($invoiceformat);
            $setting->setCodepostal($codepostal);
            $setting->setCEO($CEO);
            $setting->setCnss($cnss);
            $setting->setPatent($Patent);
            $setting->setRC($RC);
            $setting->setNIF($NIF);
            $setting->setLegalEntityType($legalEntityType);
            $setting->setEsformat($Esformat);
            $setting->setTax($TAX);
            $this->entityManager->persist($setting);
            $this->entityManager->flush();
            return $setting;
        }

        public function AddCompany($CompanyName,$userid){
             $setting = new Company();
             $setting->setCompanyName($CompanyName);
             $setting->setUserId($userid);
             $this->entityManager->persist($setting);
             $this->entityManager->flush();
             return $setting;
        }


 



    public function editSetting($CompanyName, $Logo, $Language, $SkuFormat, $ICE, $DarkMode, $Currency,  $Country, $CompanyCity, $CompanyAddress, $CompanyPhone, $CompanyEmail,$userid,$idcompany,$blformat ,$invoiceformat ,$legalEntityType, $NIF, $RC , $Patent,$cnss, $CEO, $codepostal , $TAX,$Esformat ){
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
            $setting->setICE($ICE);
            $setting->setDarkMode($DarkMode);
            $setting->setCurrency($Currency);
            $setting->setCountry($Country);
            $setting->setCompanyCity($CompanyCity);
            $setting->setCompanyAddresse($CompanyAddress);
            $setting->setCompanyPhone($CompanyPhone);
            $setting->setCompanyEmail($CompanyEmail);
            $setting->setCreator($userid);
            $setting->setBlformat($blformat);
            $setting->setInvoiceformat($invoiceformat);
            $setting->setCodepostal($codepostal);
            $setting->setCEO($CEO);
            $setting->setCnss($cnss);
            $setting->setPatent($Patent);
            $setting->setRC($RC);
            $setting->setNIF($NIF);
            $setting->setEsformat($Esformat);
            $setting->setTax($TAX);
            $setting->setLegalEntityType($legalEntityType);

            $this->entityManager->persist($setting);
            $this->entityManager->flush();

            return $setting;
        } catch (\Throwable $th) {

            throw $th;
        }
    }


      public function editCompany($CompanyName, $userid, $idcompany) {
    try {
        $Company = $this->entityManager->getRepository('Application\Entity\Company')->find($idcompany);
        
        if ($Company === null) {
             $Company = new Company();
            $Company->setCompanyName($CompanyName);
            $Company->setUserId($userid);  
        }
        
        $Company->setCompanyName($CompanyName);
        $Company->setUserId($userid);
        
        $this->entityManager->persist($Company);
        $this->entityManager->flush();
        
        return $Company;
    } catch (\Throwable $th) {
        error_log($th->getMessage());
        throw $th;
    }
}

 

    public function getAllCategorys(){
            try {
                $query = $this->entityManager->createQueryBuilder()
                        ->from('Application\Entity\Category','c') 
                        ->innerJoin("Application\Entity\user", "u","WITH", "u.id = c.createdBy")
                        ->select('c as category,c.categoryName,c.createdBy,c.description,c.categoryCode,c.id,c.logo ')
                        ->addselect('u.fullname as createdBy') ;
                        // ->Where("s.creator = ".$userid)
                        // ->AndWhere("s.id = ".$idcompany);
                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return 'khaaaaaaaaawi';
            }
        }


        
    
     
    public function addCategory($categoryName, $description, $categoryCode,$Logo,$userid){
        // $auth = $this->plugin('auth');
        // $company = $auth->hasCompany();
        // $companyid = $company['id'];
            $Category = new Category();
            $Category->setCategoryName($categoryName);
            $Category->setLogo($Logo);
            $Category->setDescription($description);
            $Category->setCategoryCode($categoryCode);
            $Category->setCreatedBy($userid);
            $this->entityManager->persist($Category);
            $this->entityManager->flush();
            return $Category;
        }


 

      public function editCategory($categoryName, $description, $categoryCode, $Logo, $userid,$categoryId){
        try {
            $category = $this->entityManager->getRepository('Application\Entity\Category')->find($categoryId);
            if (!$category) {
                echo "Category not found";
            }
      
            if(!empty($Logo)){
                  $category->setLogo($Logo);
            }
            $category->setCategoryName($categoryName);
            $category->setDescription($description);
            $category->setCategoryCode($categoryCode);
            $category->setCreatedBy($userid);
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $category;
        } catch (\Throwable $th) {

            throw $th;
        }
    }

    // delete  category
  public function deleteCategory($idcategory){
             $category = $this->entityManager->getRepository(Category::class)->find($idcategory);
            $this->entityManager->remove($category);
            $this->entityManager->flush();
            return true;
    }






    // sub category Section 
     public function getAllSubCategorys(){
            try {
                $query = $this->entityManager->createQueryBuilder()
                        ->from('Application\Entity\SubCategory','c') 
                         ->innerJoin("Application\Entity\Category", "ca","WITH", "ca.id = c.IdCategory")
                          ->innerJoin("Application\Entity\user", "u","WITH", "u.id = c.createdBy")
                        ->select('c as sub,c.SubCategoryName,c.IdCategory,c.createdBy,c.description,c.id')
                        ->addselect('ca.categoryName as categoryname') 
                        ->addselect('u.fullname as createdBy') ;
                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return 'null';
            }
        }


         public function addsubCategory($categoryName, $description, $subcategoryname,$userid){
            $SubCategory = new SubCategory();
            $SubCategory->setSubCategoryName($subcategoryname);
            $SubCategory->setDescription($description);
            $SubCategory->setIdCategory($categoryName);
            $SubCategory->setCreatedBy($userid);
            $this->entityManager->persist($SubCategory);
            $this->entityManager->flush();
            
            return $SubCategory;
        }
        
        // edit sub category
        public function editsubCategory($categoryName, $description, $SubCategoryName, $userid,$idsubcategory){
            try {
                $SubCategory = $this->entityManager->getRepository('Application\Entity\SubCategory')->find($idsubcategory);
                if (!$SubCategory) {
                    echo "Category not found";
                }
        
              
                $SubCategory->setSubCategoryName($SubCategoryName);
                $SubCategory->setDescription($description);
                $SubCategory->setIdCategory($categoryName);
                $SubCategory->setCreatedBy($userid);
                $this->entityManager->persist($SubCategory);
                $this->entityManager->flush();

                return $SubCategory;
            } catch (\Throwable $th) {

            throw $th;
        }
    }
    
       // delete  sub category
  public function deleteSubCategory($idsubcategory){
             $subcategory = $this->entityManager->getRepository(SubCategory::class)->find($idsubcategory);
            $this->entityManager->remove($subcategory);
            $this->entityManager->flush();
            return true;
    }




   // languages section 

        public function getlanguages(){
            try {
                $query = $this->entityManager->createQueryBuilder()
                        ->from('Application\Entity\Translations','t')  
                        ->select('t as tr , t.ar,t.origin,t.fr,t.id')  ;
                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return 'null';
            }
        }


        public function addTranslation($origin, $french, $arabic) {
    // Validate input data
    // if (empty($origin) || empty($french) || empty($arabic)) {
    //     return false; // or throw an exception
    // }

    // Create a new Translation entity
    $translation = new Translations();
    $translation->setOrigin($origin);
    $translation->setFr($french);
    $translation->setAr($arabic);

    try {
        // Persist the new translation to the database
        $this->entityManager->persist($translation);
        $this->entityManager->flush();

        return $translation; // Return the created translation entity
    } catch (\Exception $e) {
        // Handle any exceptions
        // Optionally log the exception or handle it accordingly
        return false;
    }
}

public function editTranslation($id, $origin, $french, $arabic) {
    try {
        // Find the translation entity by its ID
        $translation = $this->entityManager->getRepository(Translations::class)->find($id);
        // if (!$translation) {
        //     return false; // or throw an exception
        // }

        // Update the translation entity
        $translation->setOrigin($origin);
        $translation->setFr($french);
        $translation->setAr($arabic);

        // Persist changes to the database
        $this->entityManager->persist($translation);
        $this->entityManager->flush();

        return $translation; // Return the updated translation entity
    } catch (\Exception $e) {
        // Handle any exceptions
        // Optionally log the exception or handle it accordingly
        return false;
    }
}
public function deleteTranslation($id) {
    try {
        // Find the translation entity by its ID
        $translation = $this->entityManager->getRepository(Translations::class)->find($id);
        if (!$translation) {
            return false; // or throw an exception
        }

        // Remove the translation entity
        $this->entityManager->remove($translation);
        $this->entityManager->flush();

        return true; // Indicate successful deletion
    } catch (\Exception $e) {
        // Handle any exceptions
        // Optionally log the exception or handle it accordingly
        return false;
    }
}


}