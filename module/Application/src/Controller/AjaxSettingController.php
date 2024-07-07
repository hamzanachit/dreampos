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
            // Decode JSON data from request
        $postdata = json_decode($request->getContent(), true);
        $files = $request->getFiles();
        $Logo = '';
        $categoryName = htmlspecialchars($postdata['name'] ?? '');
        $description = htmlspecialchars($postdata['description'] ?? '');
        $categoryCode = htmlspecialchars($postdata['code'] ?? '');

        // Handle logo upload
        $uploadPath = 'public/img/category/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if (!empty($files['Logo']['name'])) {
            $Logo = basename($files['Logo']['name']);
            $tempPath = $files['Logo']['tmp_name'];
            $uploadFile = $uploadPath . DIRECTORY_SEPARATOR . $Logo;
            move_uploaded_file($tempPath, $uploadFile);
        }

        $userid = 2; 
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

    }