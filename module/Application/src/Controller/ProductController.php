<?php

namespace Application\Controller;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Service\ProductService;
use Laminas\Http\Response as HttpResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Application\Entity\Product;
use Doctrine\ORM\EntityManager;
use Laminas\Http\PhpEnvironment\Request;
 
  
class ProductController extends AbstractActionController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function listAction(){ 
        $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $iduser = $user['id'];
        $company = $auth->ActifCompany();
        $idcompany = $company[0]['id'];
        // dd($idcompany);
        // $FullName  = $user['fullName'];

    $products = $this->productService->getAllProducts($idcompany);
        // dd($products,$idcompany);

        return new ViewModel([
            'products' => $products,
        ]);
         
    }

    protected function jsonResponse(array $data, int $statusCode = 200): HttpResponse{
        $response = $this->getResponse();
        $response->setStatusCode($statusCode);
        $response->getHeaders()->addHeaders([
            'Content-Type' => 'application/json',
        ]);
        $response->setContent(json_encode($data));

        return $response;
    }

    public function addAction(){
        $oldskuvalue = "";
        $result = "";
        $newsku = "";
        $formatsku = "";
        $FullName  ="";
        $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $userid = $user['id'];
        $company = $auth->Company();
        $companyid = $company[0]['id'];
        $FullName  = $user['fullName'];

        $getSku = $this->productService->getSku($userid, $companyid);
        $formatsku = $getSku[0]['skuFormat'];
        $lastproductSku = $this->productService->getlastproductSku();
        // $AllCategorys = $this->productService->AllCategorys($userid);
        $oldskuvalue = $lastproductSku['sku'];
        //  dd($AllCategorys);

    if (!empty($formatsku)) {
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
    } else {
        $newsku = '';
    }

      if ($this->getRequest()->isPost()) {
            $postdata = $this->params()->fromPost();
            $files = $this->getRequest()->getFiles();
            $ProductName = htmlspecialchars($postdata['ProductName']);
            $Category = htmlspecialchars($postdata['Category']);
            $SubCategory = htmlspecialchars($postdata['SubCategory']);
            $PriceHt = htmlspecialchars($postdata['PriceHt']);
            $Unit = htmlspecialchars($postdata['Unit']);
            $SKU = htmlspecialchars($postdata['SKU']);
            $Minimum = (int)$postdata['Minimum'];
            $Quantity = (int)$postdata['Quantity'];
            $Description = htmlspecialchars($postdata['Description']);
            $Tax = (float)$postdata['Tax'];
            $Discount = (float)$postdata['Discount'];
            $Price = (float)$postdata['Price'];
            $Status = htmlspecialchars($postdata['Status']);
            $CostPrice = htmlspecialchars($postdata['CostPrice']);
            $Image = '';
            $uploadPath = 'public/img/product';
 
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
        $company = $auth->Company();
        $companyid = $company[0]['id'];
        $creator  = $user['fullName'];
        $resultadd = $this->productService->AddProduct($ProductName,$Category,$Minimum,$Quantity,$Tax,$Discount, $Price,$Status,$Image,$SubCategory,
                        $PriceHt,$Unit,$creator,$companyid,$Description ,$SKU,$CostPrice);
        return $this->redirect()->toRoute('productsActions', [
            'action' => 'list',
        ]);
    }
          return new ViewModel([
                'newsku' => $newsku,
                // 'AllCategorys' => $AllCategorys,
    ]);
}

   public function detailsAction(){
             $id = (int) $this->params()->fromRoute('id', 0);
            try {
                $product = $this->productService->getProductById($id);
            } catch (\Exception $e) {
                return $this->redirect()->toRoute('productsActions', [
                    'action' => 'index'
                ]);
            }
            return new ViewModel([
                'product' => $product
    ]);
}

   public function editAction() {
    $auth = $this->plugin('auth');
    $user = $auth->getUser();
    $userid = $user['id'];

    $productId = $this->params()->fromRoute('id', 0); // Assuming product ID is passed via route
    if (!$productId) {
        return $this->redirect()->toRoute('productsActions', ['action' => 'list']);
    }

    $product = $this->productService->getProductById($productId);
    if (!$product) {
        return $this->redirect()->toRoute('productsActions', ['action' => 'list']);
    }

    if ($this->getRequest()->isPost()) {
        $postdata = $this->params()->fromPost();
        $files = $this->getRequest()->getFiles();

        $ProductName = htmlspecialchars($postdata['ProductName']);
        $Category = htmlspecialchars($postdata['Category']);
        $SubCategory = htmlspecialchars($postdata['SubCategory']);
        $PriceHt = htmlspecialchars($postdata['PriceHt']);
        $Unit = htmlspecialchars($postdata['Unit']);
        $SKU = htmlspecialchars($postdata['SKU']);
        $Minimum = (int)$postdata['Minimum'];
        $Quantity = (int)$postdata['Quantity'];
        $Description = htmlspecialchars($postdata['Description']);
        $Tax = (float)$postdata['Tax'];
        $Discount = (float)$postdata['Discount'];
        $Price = (float)$postdata['Price'];
        $Status = htmlspecialchars($postdata['Status']);
        $CostPrice = htmlspecialchars($postdata['CostPrice']);


        // Keep old image if no new image is uploaded
        $Image = $product->getImage(); 
        $uploadPath = 'public/img/product';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Check if a new image is uploaded
        if (!empty($files['image']['name'])) {
            $Image = basename($files['image']['name']);
            $tempPath = $files['image']['tmp_name'];
            $uploadFile = $uploadPath . DIRECTORY_SEPARATOR . $Image;
            if (is_uploaded_file($tempPath)) {
                if (move_uploaded_file($tempPath, $uploadFile)) {
                    echo 'File uploaded successfully.';
                } else {
                    echo 'Failed to upload file.';
                    return $this->redirect()->toRoute('productsActions', ['action' => 'edit', 'id' => $productId]);
                }
            } else {
                echo 'No file uploaded or invalid file.';
            }
        }
        $company = $auth->Company();
        $companyid = $company[0]['id'];
        $creator  = $user['fullName'];
        // Update the product
        $resultupdate = $this->productService->editProduct(                   
        $ProductId,$ProductName,$Category,$Minimum,$Quantity,$Tax,$Discount,$Price,$Status,$Image,$SubCategory,$PriceHt,$Unit,$creator,$companyid,$userid,$Description,$SKU,$CostPrice);

        return $this->redirect()->toRoute('productsActions', [
            'action' => 'list',
        ]);
    }

    return new ViewModel([
        'product' => $product
    ]);
}

 
public function deleteAction()
{
    $request = $this->getRequest();

    if ($request->isPost()) {
        $data = json_decode($request->getContent(), true);
        $productId = $data['id'];

        try {
            $result = $this->productService->deleteProduct($productId);
            
            if ($result) {
                return $this->jsonResponse(['status' => 'success', 'message' => 'Product deleted successfully.']);
            } else {
                return $this->jsonResponse(['status' => 'error', 'message' => 'Failed to delete product.']);
            }
        } catch (\Exception $e) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    return $this->jsonResponse(['status' => 'error', 'message' => 'Invalid request method.'], 400);
}



    public function importAction(){ 
        $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $iduser = $user['id'];
        $company = $auth->ActifCompany();
        $idcompany = $company[0]['id'];
        // $FullName  = $user['fullName'];
        // dd($company);
            // dd( $_SESSION['company']);

        return new ViewModel();
     
    }
 
    // public function importAction()
    // {
    //     $company = $auth->Company();
    //     $idcompany = $company[0]['id'];
    //     dd($idcompany);
    //     return new ViewModel();
    // }
    
 
public function processImportAction()
{
     ob_start();
    // Debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $auth = $this->plugin('auth');
    $user = $auth->getUser();
    $userid = $user['id'];
    $fullName = $user['fullName'];
    $company = $auth->ActifCompany();
    $idCompany = $company[0]['id'];

    if ($this->getRequest()->isPost()) {
        try {
            $file = $this->params()->fromFiles('file');
            if (!$file || !$file['tmp_name']) {
                throw new \Exception('No file uploaded');
            }

            $filePath = $file['tmp_name'];
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $addedCount = 0;
            $updatedCount = 0;

            foreach ($sheetData as $index => $row) {
                if ($index == 1) {
                    continue;
                }

                if (empty($row['A'])) {
                    break;
                }

                $ProductName = $row['A'];
                $SKU = $row['B'];
                $Category = $row['C'];
                $oldQuantity = $row['D'];
                $Minimum = $row['E'];
                $Discount = $row['F'];
                $OldPrice = $row['G'];
                $OldPriceHt = $row['H'];
                $Status = $row['I'];
                $Unit = $row['J'];
                $SubCategory = $row['K'];
                $Image = $row['L'];
                $Tax = $row['M'];
                $Description = $row['N'];
                $OldCostPrice = $row['O'];
                $creator = $fullName;
                $companyid = $idCompany;

                $existingProduct = $this->productService->findProductBySKU($SKU, $companyid);
                if ($existingProduct) {
                    $product = $existingProduct[0];
                    $Quantity = $product->getQuantity() + $oldQuantity;
                    $Price = ($OldPrice != $product->getPrice()) ? $OldPrice : $product->getPrice();
                    $PriceHt = ($OldPriceHt != $product->getPriceHt()) ? $OldPriceHt : $product->getPriceHt();
                    $CostPrice = ($OldCostPrice != $product->getCostPrice()) ? $OldCostPrice : $product->getCostPrice();
                    $ProductId = $product->getId();
                    $this->productService->editProduct($ProductId, $ProductName, $Category, $Minimum, $Quantity, $Tax, $Discount, $Price, $Status, $Image, $SubCategory, $PriceHt, $Unit, $creator, $companyid, $userid, $Description, $SKU, $CostPrice);
                    $updatedCount++;
                } else {
                    $Quantity = $row['D'];
                    $Price = $row['G'];
                    $PriceHt = $row['H'];
                    $CostPrice = $row['O'];

                    // Add debug logging for added products
                    error_log('Adding product: ' . json_encode($row));

                    $this->productService->AddProduct($ProductName, $Category, $Minimum, $Quantity, $Tax, $Discount, $Price, $Status, $Image, $SubCategory, $PriceHt, $Unit, $creator, $companyid, $Description, $SKU, $CostPrice);
                    $addedCount++;
                }
            }

            // Simplified message
            $message = "Import successful. Added: $addedCount, Updated: $updatedCount products.";
            // Encode message to ensure proper JSON format
            ob_end_clean();
            return new JsonModel(['success' => true, 'message' => utf8_encode($message)]);
        } catch (\Exception $e) {
            ob_end_clean();
            // Log the detailed error message
            error_log('Import error: ' . $e->getMessage());
            // Ensure the message is properly encoded
            return new JsonModel(['success' => false, 'message' => utf8_encode('Failed to import products: ' . $e->getMessage())]);
        }
    }
ob_end_clean();
    return new JsonModel(['success' => false, 'message' => 'Invalid request method.']);
}



}