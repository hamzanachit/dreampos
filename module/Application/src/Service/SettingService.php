<?php

    namespace Application\Service; 

    use Doctrine\ORM\EntityManager;
    use Application\Entity\Setting;
    use Application\Entity\User;
    use Application\Entity\Category;
    use Application\Entity\SubCategory;

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


}