<?php

namespace Application\Controller;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\ProductService;

class ProductController extends AbstractActionController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function listAction(){ 

    $products = $this->productService->getAllProducts();
        return new ViewModel([
            'products' => $products,
        ]);
         
    }

     public function addAction(){

        $oldskuvalue = "";
        $result = "";
        $newsku = "";
        $formatsku = "";

        $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $userid = $user['id'];
        $getSku = $this->productService->getSku($userid);
        $formatsku = $getSku[0]['skuFormat'];
        $lastproductSku = $this->productService->getlastproductSku();
        $oldskuvalue = $lastproductSku['sku'];

        if (empty($oldskuvalue)) {
            
            $newsku = $formatsku . '1';
        } else {
            if (strpos($oldskuvalue, $formatsku) === 0) {

                $result = str_replace($formatsku, '', $oldskuvalue);
                if (is_numeric($result)) {
                    $newsku = $formatsku . ($result + 1);
                } else { 

                    $newsku = $formatsku . '1';
                }
                
            } else {
                $newsku = $formatsku . '1';
            }
        }
//   dd($result ,$formatsku , $newsku);
     if ($this->getRequest()->isPost()) {
            $postdata = $this->params()->fromPost();
            $files = $this->getRequest()->getFiles();
            $ProductName = htmlspecialchars($postdata['ProductName']);
            $Category = htmlspecialchars($postdata['Category']);
            $SubCategory = htmlspecialchars($postdata['SubCategory']);
            $Brand = htmlspecialchars($postdata['Brand']);
            $Unit = htmlspecialchars($postdata['Unit']);
            $SKU = htmlspecialchars($postdata['SKU']);
            $Minimum = (int)$postdata['Minimum'];
            $Quantity = (int)$postdata['Quantity'];
            $Description = htmlspecialchars($postdata['Description']);
            $Tax = (float)$postdata['Tax'];
            $Discount = (float)$postdata['Discount'];
            $Price = (float)$postdata['Price'];
            $Status = htmlspecialchars($postdata['Status']);
            $Image = '';
            $uploadPath = 'public/img/product';
        // dd($SKU);

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if (!empty($files['image']['name'])) {
            $Image = basename($files['image']['name']);
            $tempPath = $files['image']['tmp_name'];
            $uploadFile = $uploadPath . DIRECTORY_SEPARATOR . $Image;

            // Move uploaded file to designated directory
            if (is_uploaded_file($tempPath)) {
                if (move_uploaded_file($tempPath, $uploadFile)) {
                    echo 'File uploaded successfully.';
                } else {
                    echo 'Failed to upload file.';
                    return $this->redirect()->toRoute('productsActions', ['action' => 'add']);
                }
            } else {
                echo 'No file uploaded or invalid file.';
            }
        }
 
        $resultadd = $this->productService->AddProduct(
            $ProductName, $Category, $Description, $Minimum, $Quantity, $Tax, $Discount, $Price, $Status, $Image, $SubCategory, $Brand, $Unit, $SKU
        );
      

        return $this->redirect()->toRoute('productsActions', [
            'action' => 'add',
        ]);
    }

          return new ViewModel([
                'newsku' => $newsku
    ]);
}

   public function detailsAction(){
            // Retrieve the product ID from the route parameters
            $id = (int) $this->params()->fromRoute('id', 0);
            
            // if (!$id) {
            //     return $this->redirect()->toRoute('productsActions', [
            //         'action' => 'index'
            //     ]);
            // }

            // Fetch the product details from the service
            try {
                $product = $this->productService->getProductById($id);
            } catch (\Exception $e) {
                return $this->redirect()->toRoute('productsActions', [
                    'action' => 'index'
                ]);
            }

            // Pass the product details to the view
            return new ViewModel([
                'product' => $product
    ]);
}













 }