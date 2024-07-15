<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Application\Service\SettingService;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;

class AjaxSettingController extends AbstractActionController
{
    protected $settingService;
    protected $logger;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;

         
    }

    public function addAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postdata = json_decode($request->getContent(), true);
            
            // Extract form data
            $categoryName = htmlspecialchars($postdata['name'] ?? '');
            $description = htmlspecialchars($postdata['description'] ?? '');
            $categoryCode = htmlspecialchars($postdata['code'] ?? '');
            
            // Handle uploaded image
            $Logo = '';
            $uploadPath = 'public/img/category/';

            // Check if the 'image' field exists and is not empty
            if (isset($postdata['image']) && !empty($postdata['image'])) {
                $imageData = base64_decode($postdata['image']);
                $Logo = uniqid() . '.png'; // Generate a unique filename
            $uploadFile = $uploadPath . DIRECTORY_SEPARATOR . $Logo;
            
            // Save the decoded image data to the file
            file_put_contents($uploadFile, $imageData);
        }

        // Optionally, retrieve user ID for logging purposes
        $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $userid = $user['id'];

        // Save category data
        $resultAdd = $this->settingService->addCategory($categoryName, $description, $categoryCode, $Logo, $userid);

        if ($resultAdd) {
            return new JsonModel([
                'success' => true,
                'message' => 'Category added successfully'
            ]);
        } else {
            return new JsonModel([
                'success' => false,
                'message' => 'Failed to add category'
            ]);
        }
    }
}



    // edit category 
     public function editcategoryAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postdata = json_decode($request->getContent(), true);
            $categoryName = htmlspecialchars($postdata['name'] ?? '');
            $description = htmlspecialchars($postdata['description'] ?? '');
            $categoryCode = htmlspecialchars($postdata['code'] ?? '');
            $categoryId = htmlspecialchars($postdata['idcategory'] ?? '');
            // dd($idcategory);
            $Logo = '';
            $uploadPath = 'public/img/category/';
             if (isset($postdata['image']) && !empty($postdata['image'])) {
                $imageData = base64_decode($postdata['image']);
                $Logo = uniqid() . '.png';
                $uploadFile = $uploadPath . DIRECTORY_SEPARATOR . $Logo;
                
             file_put_contents($uploadFile, $imageData);
        }
 
        // Optionally, retrieve user ID for logging purposes
        $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $userid = $user['id'];
        $resultAdd = $this->settingService->editCategory($categoryName, $description, $categoryCode, $Logo, $userid,$categoryId);

        if ($resultAdd) {
            return new JsonModel([
                'success' => true,
                'message' => 'Category edited successfully'
            ]);
        } else {
            return new JsonModel([
                'success' => false,
                'message' => 'Failed to edited category'
            ]);
        }
    }
}




     function deletecategoryAction(){
        $request = $this->getRequest();
            if ($request->isPost()) {
            $idcategory = json_decode($request->getContent(), true);
            $resultDelete = $this->settingService->deleteCategory($idcategory);
        if ($resultDelete) {
            return new JsonModel([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } else {
            return new JsonModel([
                'success' => false,
            'message' => 'Failed to delete category'
        ]);
    }
}
}



    // section sub category 
 public function addsubcategoryAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postdata = json_decode($request->getContent(), true);
            
            // Extract form data
            $description = htmlspecialchars($postdata['description'] ?? '');
            $subcategoryname = htmlspecialchars($postdata['subcategoryname'] ?? '');
            $categoryName = htmlspecialchars($postdata['categoryname'] ?? '');

            
            // Handle uploaded image
          
        }

         $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $userid = $user['id'];
        $resultAdd = $this->settingService->addsubCategory($categoryName, $description, $subcategoryname, $userid);
        if ($resultAdd) {
            return new JsonModel([
                'success' => true,
                'message' => 'Sub Category added successfully'
            ]);
        } else {
            return new JsonModel([
                'success' => false,
                'message' => 'Failed to add sub category'
            ]);
        }
    }


      public function editsubcategoryAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postdata = json_decode($request->getContent(), true);
            $categoryName = htmlspecialchars($postdata['categoryNameedit'] ?? '');
            $description = htmlspecialchars($postdata['description'] ?? '');
            $SubCategoryName= htmlspecialchars($postdata['SubCategoryNameedit'] ?? '');
            $idsubcategory = htmlspecialchars($postdata['idsubcategory'] ?? '');
            // dd($idcategory);
         
        }
 
        // Optionally, retrieve user ID for logging purposes
        $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $userid = $user['id'];
         $resultAdd = $this->settingService->editsubCategory($categoryName, $description, $SubCategoryName, $userid,$idsubcategory);
        if ($resultAdd) {
            return new JsonModel([
                'success' => true,
                'message' => 'Sub Category edited successfully'
            ]);
        } else {
            return new JsonModel([
                'success' => false,
                'message' => 'Failed to edited Sub category'
            ]);
        }
    }




    
 function deleteSubCategoryAction(){
        $request = $this->getRequest();
            if ($request->isPost()) {
            $idsubcategory = json_decode($request->getContent(), true);
            $resultDelete = $this->settingService->deleteSubCategory($idsubcategory);
        if ($resultDelete) {
            return new JsonModel([
                'success' => true,
                'message' => 'Sub Category deleted successfully'
            ]);
        } else {
            return new JsonModel([
                'success' => false,
            'message' => 'Failed to delete Sub category'
        ]);
    }
}
}









}


 




 