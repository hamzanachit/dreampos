<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Service\SalesService; 
use NumberToWords\NumberToWords;
class SalesController extends AbstractActionController
{
    private $salesservice;

    public function __construct(SalesService $salesservice)
    {
        $this->salesservice = $salesservice;
    }

    public function listAction(){

        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
        $currency = $company[0]['currency'];


        $orders = $this->salesservice->getOrdersByCompany($companyId);
// dd($orders);
        return new ViewModel([
            'orders' => $orders,
            'currency' => $currency,
        ]);

    }



    public function addAction(){

        $auth = $this->plugin('auth');
        $user = $auth->getUser();
        $userid = $user['id'];
        $fullName = $user['fullName'];
        $company = $auth->ActifCompany();
        $idCompany = $company[0]['id'];
 
        $currency = $company[0]['currency'];
        $customers = $this->salesservice->getcustomers($idCompany);

        // dd($customers);
         return new ViewModel([
            'customers'  => $customers,
            'currency' => $currency,
            ]);

    }

 
   public function addOrderAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = json_decode($request->getContent(), true);

            try {

                $auth = $this->plugin('auth');
                $user = $auth->getUser();
                $createdBy = $user['id'];
                $company = $auth->ActifCompany();
                $idcompany = $company[0]['id']; 
            //  dd(  $data);
                $order = $this->salesservice->addOrder($data,$idcompany,$createdBy);

                return new JsonModel([
                    'success' => true,
                    'orderId' => $order->getId()
                ]);
            } catch (\Exception $e) {
                return new JsonModel([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }

        return new JsonModel([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }

    public function updateOrderAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = json_decode($request->getContent(), true);
            $orderId = $this->params()->fromQuery('orderId');

            try {

                 $auth = $this->plugin('auth');
                $user = $auth->getUser();
                $createdBy = $user['id'];
                $company = $auth->ActifCompany();
                $idcompany = $company[0]['id']; 
                $order = $this->salesservice->updateOrder($orderId, $data,$idcompany,$createdBy);

                return new JsonModel([
                    'success' => true,
                    'orderId' => $order->getId()
                ]);
            } catch (\Exception $e) {
                return new JsonModel([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }

        return new JsonModel([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }
 

    public function getProductsAction() {
        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];

        // Retrieve the query parameter from the request
        $query = $this->params()->fromQuery('query', '');

        // Call the SalesService to get products
        $products = $this->salesservice->getProducts($companyId, $query);

        return new JsonModel([
            'success' => true,
            'products' => $products
        ]);
    }



    public function getCustomersAction(){
        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];

        $customers = $this->getCustomers($companyId);

        return new JsonModel([
            'success' => true,
            'customers' => $customers
        ]);
    }




public function getOrderDetailsAction()
{
    $orderId = $this->params()->fromQuery('id');
      $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
  
    $order = $this->salesservice->getOrderDetails($orderId,$companyId); // Assume this service returns an array of order details
    
    if ($order) {
        return new JsonModel([
            'success' => true,
            'order' => $order
        ]);
    } else {
        return new JsonModel([
            'success' => false,
            'message' => 'Order not found'
        ]);
    }
}


 
        public function blAction(){
        $orderId = $this->params()->fromRoute('id', 0);
        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
        // dd(  $company );

        $companyinfo = [];
        
       $companyinfo = [
                'companyName' => $company[0]['companyName'],
                'skuFormat' => $company[0]['skuFormat'],
                'ICE' => $company[0]['ICE'],
                'country' => $company[0]['country'],
                'companyCity' => $company[0]['companyCity'],
                'companyAddresse' => $company[0]['companyAddresse'],
                'companyPhone' => $company[0]['companyPhone'],
                'companyEmail' => $company[0]['companyEmail'],
                'blformat' => $company[0]['blformat'],
                'fullname' => $company[0]['fullname'],
                'fullname' => $company[0]['currency'],
             ];

 

// dd(  $company );
        // Get order details including items
        $order = $this->salesservice->getOrderDetails($orderId, $companyId);
        if ($order) {
             $viewModel = new ViewModel([
                'order' => $order[0], // Assuming getOrderDetails returns an array of orders
                'company' => $companyinfo , 
                'salesservice' => $this->salesservice, 
                'currency' => $company[0]['currency'], 
                'orderItems' => $order[0]['items'] ?? [] // Extract order items
            ]);

            // $viewModel->setTerminal(true);  
            // $viewModel->setTemplate('application/Sales/BLPdf');
               $viewModel->setTerminal(true);
               $viewModel->setTemplate('application/Sales/BLPdf');
                return $viewModel;
            
        } else {
            return new JsonModel([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }
    }





    public function editAction()
    {
        // Logic to handle editing a sales order
    }

    public function deleteAction()
    {
        // Logic to handle deleting a sales order
    }
}