<?php
// File: module/Application/src/Controller/AuthController.php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Laminas\Db\Adapter\Adapter;
use Laminas\Session\Container;
use Laminas\Db\Sql\Sql;
use Application\Service\DashboardService;

class AuthController extends AbstractActionController
{
    protected $dbAdapter;
    protected $authService;
    protected $DashboardService;

    public function __construct(Adapter $dbAdapter, AuthenticationService $authService, DashboardService $DashboardService)
    {
        $this->dbAdapter = $dbAdapter;
        $this->authService = $authService;
        $this->DashboardService = $DashboardService;
    }

    public function loginAction()
    {
        if ($this->authService->hasIdentity()) {
            return $this->redirect()->toRoute('dashboard');
        }

        $viewModel = new ViewModel();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();

            $authAdapter = new CallbackCheckAdapter(
                $this->dbAdapter,
                'user',
                'email',
                'password',
                function ($hash, $password) {
                    return password_verify($password, $hash);
                }
            );

            $authAdapter
                ->setIdentity($data['email'])
                ->setCredential($data['password']);

            $this->authService->setAdapter($authAdapter);
            $result = $this->authService->authenticate();

            if ($result->isValid()) {
                $userTable = new \Laminas\Db\TableGateway\TableGateway('user', $this->dbAdapter);
                $user = $userTable->select(['email' => $data['email']])->current();
                if ($user) {
                    $session = new Container('user_session');
                    $session->user = $user;

                    $_SESSION["user"] =$user;

                    $this->authService->getStorage()->write($user);
                       $user = $this->authService->getIdentity();
                        $userid = $user['id'];
                        $checkcompany = "";
                        
                       
                        if ($this->DashboardService){
                             $checkcompany = $this->DashboardService->getCheckCompanyById($userid);

                         if ($checkcompany == null) {
                            
                            $session = new Container('company_session');
                            $session->company = $checkcompany;
                            $_SESSION["company"] =  $checkcompany;
                            
                         } 
                            return $this->redirect()->toRoute('dashboard');

                    }
                } else {
                    $viewModel->setVariable('error', 'User not found.');
                }
            } else {
                $viewModel->setVariable('error', 'Invalid credentials.');
            }
        }
        $viewModel->setTerminal(true);
        $viewModel->setTemplate('application/auth/login');
        return $viewModel;
    }

    public function registerAction()
    {
        if ($this->authService->hasIdentity()) {
            return $this->redirect()->toRoute('dashboard');
        }

        $viewModel = new ViewModel();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            if (empty($data['full_name']) || empty($data['email']) || empty($data['phone']) || empty($data['address']) || empty($data['password'])) {
                $viewModel->setVariable('error', 'All fields are required.');
                return $viewModel;
            }

            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('user')->where(['email' => $data['email']]);
            $statement = $sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();

            if ($result->current()) {
                $viewModel->setVariable('error', 'Email already exists.');
                return $viewModel;
            }

            $insert = $sql->insert('user');
            $insert->values([
                'fullName' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'status' => 'active',
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ]);
 
            $statement = $sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();
            if ($result->getAffectedRows()) {
                return $this->redirect()->toRoute('login');
            } else {
                $viewModel->setVariable('error', 'Registration failed.');
            }
        }

        $viewModel->setTemplate('application/auth/register');
        return $viewModel;
    }

    // public function logoutAction()
    // {
    //     $this->authService->clearIdentity();

    //     $session = new Container('user_session');
    //     $session->getManager()->destroy();
    //     $_SESSION["user"] = "";
    //     // $session = new Container('company_session');
    //     $session->company = "";
    //     $_SESSION["company"] = "";
    //     return $this->redirect()->toRoute('login');
    // }

    public function logoutAction(){
            $this->authService->clearIdentity(); 
            $session = new Container('user_session');
            $session->getManager()->destroy();
            $session->company = ""; 
         return $this->redirect()->toRoute('login');
    }

}