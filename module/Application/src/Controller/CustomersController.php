<?php

namespace Application\Controller;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Service\CustomersService;
use Laminas\Http\Response as HttpResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Application\Entity\Product;
use Doctrine\ORM\EntityManager;
use Laminas\Http\PhpEnvironment\Request;
 
  
class CustomersController extends AbstractActionController
{
    protected $CustomersService;

    public function __construct(CustomersService $CustomersService)
    {
        $this->CustomersService = $CustomersService;
    }
  public function listAction(){ 
        $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $iduser = $user['id'];
        $company = $auth->ActifCompany();
        $idcompany = $company[0]['id'];
        $AllCustomers = $this->CustomersService->getAllCustomers($idcompany);
        // dd($AllCustomers,$idcompany);

        return new ViewModel([
            'AllCustomers' => $AllCustomers,
        ]);
         
    }
    public function addAction(){
     ob_start();

         if ($this->getRequest()->isPost()) {
            try {
                // Clean output buffer to prevent any previous output from interfering
                ob_end_clean();

                // Retrieve JSON data from request
                $postData = json_decode($this->getRequest()->getContent(), true);

                // Sanitize and process the data
                $name = htmlspecialchars($postData['name'] ?? '');
                $email = htmlspecialchars($postData['email'] ?? '');
                $phone = htmlspecialchars($postData['phone'] ?? '');
                $ICE = htmlspecialchars($postData['ICE'] ?? '');
                $address = htmlspecialchars($postData['address'] ?? '');
                $bank =( int) htmlspecialchars($postData['Bank'] ?? '');
                $note = htmlspecialchars($postData['Note'] ?? '');
                $maxamount = htmlspecialchars($postData['maxamount'] ?? '');
                $customercode = htmlspecialchars($postData['customercode'] ?? '');
                $bankname = htmlspecialchars($postData['bankname'] ?? '');
                // Retrieve user and company information
                $auth = $this->plugin('auth');
                $user = $auth->getUser();
                $userId = $user['id'];
                $company = $auth->ActifCompany();
                $idCompany = $company[0]['id']; // Ensure company[0]['id'] exists

                // Save customer data
                $resultAdd = $this->CustomersService->addCustomer($name, $email, $phone, $ICE, $address, $bank, $note, $userId, $idCompany,$bankname, $customercode, $maxamount);

                // Return JSON response
                if ($resultAdd) {
                    return new JsonModel(['success' => true, 'message' => 'Customer added successfully.']);
                } else {
                    return new JsonModel(['success' => false, 'message' => 'Failed to add customer.']);
                }
            } catch (\Exception $e) {
                // Clean output buffer in case of errors
                ob_end_clean();
                // Log the detailed error message
                error_log('Add customer error: ' . $e->getMessage());
                // Return JSON response with error message
                return new JsonModel(['success' => false, 'message' => 'Failed to add customer: ' . $e->getMessage()]);
            }
        }

        // Handle invalid request method
        ob_end_clean();
        return new JsonModel(['success' => false, 'message' => 'Invalid request method.']);
    }
 

    public function editAction(){
      ob_start();

        if ($this->getRequest()->isPost()) {
            try {
                // Clean output buffer to prevent any previous output from interfering
                ob_end_clean();

                // Retrieve JSON data from request
                $postData = json_decode($this->getRequest()->getContent(), true);

                // Sanitize and process the data
                $id = htmlspecialchars($postData['id'] ?? '');
                $name = htmlspecialchars($postData['name'] ?? '');
                $email = htmlspecialchars($postData['email'] ?? '');
                $phone = htmlspecialchars($postData['phone'] ?? '');
                $ICE = htmlspecialchars($postData['ICE'] ?? '');
                $address = htmlspecialchars($postData['address'] ?? '');
                $bank = htmlspecialchars($postData['Bank'] ?? '');
                $note = htmlspecialchars($postData['Note'] ?? '');
                $maxamount = htmlspecialchars($postData['maxamount'] ?? '');
                $customercode = htmlspecialchars($postData['customercode'] ?? '');
                $bankname = htmlspecialchars($postData['bankname'] ?? '');
                $image = $postData['image'] ?? ''; // Base64 encoded image data

                // Retrieve user and company information
                $auth = $this->plugin('auth');
                $user = $auth->getUser();
                $userId = $user['id'];
                $company = $auth->ActifCompany();
                $idCompany = $company[0]['id']; // Ensure company[0]['id'] exists

                // Update customer data
                $resultEdit = $this->CustomersService->editCustomer($id, $name, $email, $phone, $ICE, $address, $bank, $note, $userId, $idCompany, $image, $bankname, $customercode, $maxamount);

                // Return JSON response
                if ($resultEdit) {
                    return new JsonModel(['success' => true, 'message' => 'Customer updated successfully.']);
                } else {
                    return new JsonModel(['success' => false, 'message' => 'Failed to update customer.']);
                }
            } catch (\Exception $e) {
                // Clean output buffer in case of errors
                ob_end_clean();
                // Log the detailed error message
                error_log('Edit customer error: ' . $e->getMessage());
                // Return JSON response with error message
                return new JsonModel(['success' => false, 'message' => 'Failed to update customer: ' . $e->getMessage()]);
            }
        }

        // Handle invalid request method
        ob_end_clean();
        return new JsonModel(['success' => false, 'message' => 'Invalid request method.']);
    }

    public function deleteAction() {
        ob_start();
        if ($this->getRequest()->isPost()) {
            try {
                // Clean output buffer to prevent any previous output from interfering
                ob_end_clean();

                // Retrieve JSON data from request
                $postData = json_decode($this->getRequest()->getContent(), true);

                // Sanitize and process the data
                $id = htmlspecialchars($postData['id'] ?? '');

                $resultDelete = $this->CustomersService->deleteCustomer($id);

            // Return JSON response
            if ($resultDelete) {
                return new JsonModel(['success' => true, 'message' => 'Customer Deleted successfully.']);
            } else {
                return new JsonModel(['success' => false, 'message' => 'Failed to Delete customer.']);
            }
        } catch (\Exception $e) {
            // Clean output buffer in case of errors
            ob_end_clean();
            // Log the detailed error message
            error_log('Edit customer error: ' . $e->getMessage());
            // Return JSON response with error message
            return new JsonModel(['success' => false, 'message' => 'Failed to Delete customer: ' . $e->getMessage()]);
        }
    }

        // Handle invalid request method
        ob_end_clean();
        return new JsonModel(['success' => false, 'message' => 'Invalid request method.']);
    }



    }