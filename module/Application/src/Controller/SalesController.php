<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Service\SalesService; 
use NumberToWords\NumberToWords;
use Dompdf\Dompdf;
use Laminas\View\Renderer\RendererInterface;

class SalesController extends AbstractActionController{
    private $salesservice;
    private $viewRenderer;
    public function __construct(SalesService $salesservice, RendererInterface $viewRenderer){
        $this->salesservice = $salesservice;
        $this->viewRenderer = $viewRenderer;
    }

    public function listAction(){

        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
        $currency = $company[0]['currency'];


        $orders = $this->salesservice->getOrdersByCompany($companyId);
         return new ViewModel([
            'orders' => $orders,
            'currency' => $currency,
        ]);
         $viewModel->setTemplate('application/Sales/list');
        return $viewModel;

    }


    public function listsalesAction(){

        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
        $currency = $company[0]['currency'];


        $orders = $this->salesservice->getsalesByCompany($companyId);
         return new ViewModel([
            'orders' => $orders,
            'currency' => $currency,
        ]);

        $viewModel->setTemplate('application/Sales/listsales');
        return $viewModel;


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

 
    public function addOrderAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = json_decode($request->getContent(), true);

            try {

                $auth = $this->plugin('auth');
                $user = $auth->getUser();
                $createdBy = $user['id'];
                $company = $auth->ActifCompany();
                $idcompany = $company[0]['id']; 
                if( $data['type'] === 'BL'){

                    $order = $this->salesservice->addOrderBL($data,$idcompany,$createdBy);

                }else if( $data['type'] === 'ES'){

                    $order = $this->salesservice->addOrderES($data,$idcompany,$createdBy);

                } else if($data['type'] === 'SL'){
                    
                    $order = $this->salesservice->addOrderSL($data,$idcompany,$createdBy);
                }
            
                // $order = $this->salesservice->addOrder($data,$idcompany,$createdBy);
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

    public function blAction(){
        $orderId = $this->params()->fromRoute('id', 0);
        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
    

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


    public function generatePdfBLAction(){
        $orderId = $this->params()->fromRoute('id', 0);
        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
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
            'currency' => $company[0]['currency'],
            'logo' => $company[0]['logo'],
        ];

        $order = $this->salesservice->getOrderDetailsBL($orderId, $companyId);
        // dd( $order);
        $createdAt =  "";
        $customercode =  "";
        $datefacturation = "";
        $echeance =   "";
        
        if ($order[0]['createdAt'] != null ){
          $createdAt =  $order[0]['createdAt'];

        }

         if ($order[0]['customercode'] != null ){
         $customercode =  $order[0]['customercode'];
        }
        // dd(   $customercode);
        if ($order[0]['createdAt'] != null ){
             $datefacturation = $createdAt->format('d-m-Y');
              $echeance = $createdAt->modify('+30 days')->format('d-m-Y');


        }

        // dd( $order);
        if (!$order) {
            return new JsonModel([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }
 
        $viewModel = new ViewModel([
        'order' => $order[0],
        'company' => $companyinfo,
        'salesservice' => $this->salesservice,
        'currency' => $company[0]['currency'],
        'orderItems' => $order[0]['items'] ?? [],
        'createdAt' => $datefacturation,
        'echeance' => $echeance,
        'customercode' => $customercode,
     ]);
 
        $viewModel->setTemplate('application/Sales/BLPdf');
        $htmlContent = $this->viewRenderer->render($viewModel);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent); 
        $dompdf->setPaper('A4', 'portrait'); 
        $dompdf->render(); 
        $dompdf->stream("Facture_PROV86.pdf", array("Attachment" => 0));
        return $this->response;
    }

    public function getOrderDetailsBLAction(){
        $orderId = $this->params()->fromQuery('id');
        $auth = $this->plugin('auth');
            $company = $auth->ActifCompany();
            $companyId = $company[0]['id'];
    
        $order = $this->salesservice->getOrderDetailsBL($orderId,$companyId);  
        
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
 

    public function generatePdfSlAction(){
        $orderId = $this->params()->fromRoute('id', 0);
        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
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
            'currency' => $company[0]['currency'],
            'logo' => $company[0]['logo'],
            'invoiceformat' => $company[0]['invoiceformat'],
            ];

            $order = $this->salesservice->getOrderSLDetails($orderId, $companyId);
            // dd(   $order );
            $createdAt =  "";
            $customercode =  "";
            $datefacturation =   "";
            $echeance =   "";
            
            if ($order[0]['createdAt']!= null ){
            $createdAt =  $order[0]['createdAt'];

            }

            if ($order[0]['customercode'] != null ){
            $customercode =  $order[0]['customercode'];
            }
            // dd(   $customercode);
            if ($createdAt->format('d-m-Y') != null ){
                $datefacturation = $createdAt->format('d-m-Y');
            $echeance = $createdAt->modify('+30 days')->format('d-m-Y');
            }

                // dd( $order);
            if (!$order) {
                return new JsonModel([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
    
        $viewModel = new ViewModel([
            'order' => $order[0],
            'company' => $companyinfo,
            'salesservice' => $this->salesservice,
            'currency' => $company[0]['currency'],
            'orderItems' => $order[0]['items'] ?? [],
            'createdAt' => $datefacturation,
            'echeance' => $echeance,
            'customercode' => $customercode,
        ]);
    
        $viewModel->setTemplate('application/Sales/SL-Pdf');

        $htmlContent = $this->viewRenderer->render($viewModel);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent); 
        $dompdf->setPaper('A4', 'portrait'); 
        $dompdf->render(); 
        $dompdf->stream("Facture.pdf", array("Attachment" => 0));
        return $this->response;
    }

    public function getOrderDetailsSlAction(){
        $orderId = $this->params()->fromQuery('id');
        $auth = $this->plugin('auth');
            $company = $auth->ActifCompany();
            $companyId = $company[0]['id'];
        // dd( $order);
    
        $order = $this->salesservice->getOrderSlDetails($orderId,$companyId); // Assume this service returns an array of order details
        // dd( $order);
        if ($order != null) {
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

    public function generatePdfEsAction(){
        $orderId = $this->params()->fromRoute('id', 0);
        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
        $Esformat = $company[0]['Esformat'];
        // dd($company,$company[0]['Esformat']);
        // dd($Esformat);
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
            'currency' => $company[0]['currency'],
            'logo' => $company[0]['logo'],
            'invoiceformat' => $company[0]['invoiceformat'],
            ];
            $order = $this->salesservice->getOrderDetailsEs($orderId, $companyId);
            $createdAt =  "";
            $customercode =  "";
            $datefacturation =   "";
            $echeance =   "";
            
            if ($order[0]['createdAt']!= null ){
                $createdAt =  $order[0]['createdAt'];
            }

            if ($order[0]['customercode'] != null ){
                $customercode =  $order[0]['customercode'];
            } 
            if ($createdAt->format('d-m-Y') != null ){
                $datefacturation = $createdAt->format('d-m-Y');
                $echeance = $createdAt->modify('+30 days')->format('d-m-Y');
            } 
            
            if (!$order) {
                return new JsonModel([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
    
        $viewModel = new ViewModel([
            'order' => $order[0],
            'company' => $companyinfo,
            'salesservice' => $this->salesservice,
            'currency' => $company[0]['currency'],
            'orderItems' => $order[0]['items'] ?? [],
            'createdAt' => $datefacturation,
            'echeance' => $echeance,
            'customercode' => $customercode, 
            'Esformat' => $Esformat,
        ]);
    
        $viewModel->setTemplate('application/Sales/ESPdf');
        $htmlContent = $this->viewRenderer->render($viewModel);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent); 
        $dompdf->setPaper('A4', 'portrait'); 
        $dompdf->render(); 
        $dompdf->stream("Facture.pdf", array("Attachment" => 0));
        return $this->response;
    }

    public function getOrderDetailsEsAction(){
        $orderId = $this->params()->fromQuery('id');
        $auth = $this->plugin('auth');
            $company = $auth->ActifCompany();
            $companyId = $company[0]['id'];
        // dd( $order);
    
        $order = $this->salesservice->getOrderDetailsEs($orderId,$companyId);  
        if ($order != null) {
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

 
    public function listEstimateAction(){

        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
        $currency = $company[0]['currency'];


        $orders = $this->salesservice->getestimateByCompany($companyId);
         return new ViewModel([
            'orders' => $orders,
            'currency' => $currency,
        ]);

        $viewModel->setTemplate('application/Sales/list-estimate');
        return $viewModel;
    }


    //  edit Bond Delivery
    public function editBlAction(){ 
        $orderId = $this->params()->fromRoute('id', 0); 
         $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
        $currency = $company[0]['currency'];
        $customers = $this->salesservice->getcustomers($companyId);
         $orderDetails = $this->salesservice->getOrderDetailsBL($orderId, $companyId);
         $orderData = !empty($orderDetails) ? $orderDetails[0] : [];
        //  dd($orderData);
        return new ViewModel([
            'order' => $orderData,
            'currency' => $currency,
            'customers' => $customers,
            'orderId' => $orderId,
        ]);
        $viewModel->setTemplate('application/Sales/editbl');
        return $viewModel;
    }


    public function updateOrderAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $orderData = json_decode($request->getContent(), true);
            $orderId = $orderData['orderid']; 
            if (!$orderId) {
                return new JsonModel([
                    'success' => false,
                    'message' => 'Order ID is required'
                ]);
            }

            try {
                $auth = $this->plugin('auth');
                $user = $auth->getUser();
                $createdBy = $user['id'];
                $company = $auth->ActifCompany();
                $idcompany = $company[0]['id'];
// dd( $orderData );

                $order = $this->salesservice->updateOrder($orderId, $orderData, $idcompany, $createdBy);
            //     $oldType = $result['oldType'];
            //     $newType = $order['newType'];
            //  if ($oldType !== $newType) {
            //     $response['oldType'] = $oldType;
            //     $response['newType'] = $newType;
            // }
                return new JsonModel([
                    'success' => true,
                    'orderId' => $order->getId()
                ]);
            } catch (\Exception $e) {
                return new JsonModel([
                    'success' => false,
                    'message' => 'Error updating order: ' . $e->getMessage()
                ]);
            }
        }

        return new JsonModel([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }


    public function deleteOrderItemAction(){
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = json_decode($request->getContent(), true);
            $itemId = $data['itemId'];
            $result = $this->salesservice->deleteOrderItem($itemId);
            return new JsonModel($result);
        }

        return $this->getResponse()->setStatusCode(405);  
    }


    public function deleteOrderItemESAction(){
            $request = $this->getRequest();

            if ($request->isPost()) {
                $data = json_decode($request->getContent(), true);
                $itemId = $data['itemId'];
                $result = $this->salesservice->deleteOrderItemES($itemId);
                return new JsonModel($result);
            }

            return $this->getResponse()->setStatusCode(405);  
        }


        public function deleteOrderItemBLAction(){
            $request = $this->getRequest();

            if ($request->isPost()) {
                $data = json_decode($request->getContent(), true);
                $itemId = $data['itemId'];
                $result = $this->salesservice->deleteOrderItemBL($itemId);
                return new JsonModel($result);
            }

        return $this->getResponse()->setStatusCode(405);  
    }
     


    //  edit sale
    public function editSlAction(){ 
        $orderId = $this->params()->fromRoute('id', 0);
        // dd( $orderId) ;
         $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id'];
        $currency = $company[0]['currency'];
        $customers = $this->salesservice->getcustomers($companyId);
         $orderDetails = $this->salesservice->getOrderDetailsSl($orderId, $companyId);
        // dd( $orderDetails) ;


         $orderData = !empty($orderDetails) ? $orderDetails[0] : [];
        return new ViewModel([
            'order' => $orderData,
            'currency' => $currency,
            'customers' => $customers,
            'orderId' => $orderId,
        ]);
    }

    public function getProductsSlAction() {
        $auth = $this->plugin('auth');
        $company = $auth->ActifCompany();
        $companyId = $company[0]['id']; 
        $query = $this->params()->fromQuery('query', ''); 
        $products = $this->salesservice->getProductsSl($companyId, $query);
        return new JsonModel([
            'success' => true,
            'products' => $products
        ]);
    }

    public function updateOrderSlAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            $orderData = json_decode($request->getContent(), true);
            $orderId = $this->params()->fromRoute('id', 1); 
        
            if (!$orderId) {
                return new JsonModel([
                    'success' => false,
                    'message' => 'Order ID is required'
                ]);
            }

            try {
                $auth = $this->plugin('auth');
                $user = $auth->getUser();
                $createdBy = $user['id'];
                $company = $auth->ActifCompany();
                $idcompany = $company[0]['id'];
                $order = $this->salesservice->updateOrderSl($orderId, $orderData, $idcompany, $createdBy);
            // dd( $order );
                return new JsonModel([
                    'success' => true,
                    'orderId' => $order->getId()
                ]);
            } catch (\Exception $e) {
                return new JsonModel([
                    'success' => false,
                    'message' => 'Error updating order: ' . $e->getMessage()
                ]);
            }
        }

        return new JsonModel([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }




    public function editEsAction(){ 
            $orderId = $this->params()->fromRoute('id', 0);
            // dd( $orderId) ;
            $auth = $this->plugin('auth');
            $company = $auth->ActifCompany();
            $companyId = $company[0]['id'];
            $currency = $company[0]['currency'];
            $customers = $this->salesservice->getcustomers($companyId);
            $orderDetails = $this->salesservice->getOrderDetailsEs($orderId, $companyId);
            $orderData = !empty($orderDetails) ? $orderDetails[0] : [];
            return new ViewModel([
                'order' => $orderData,
                'currency' => $currency,
                'customers' => $customers,
                'orderId' => $orderId,
            ]);
            
            $viewModel->setTemplate('application/Sales/edites');
            return $viewModel;
    }


 
}