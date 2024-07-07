<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Laminas\Db\Adapter\Adapter;
use Laminas\Authentication\Storage\Session as SessionStorage;
use Laminas\Db\Sql\Sql;
use Laminas\Session\SessionManager;
use Laminas\Session\Container;
use Application\Entity\User;

class AuthController extends AbstractActionController
{
    protected $dbAdapter;
    protected $authService;
     protected $sessionManager;

    public function __construct(Adapter $dbAdapter, AuthenticationService $authService, SessionManager $sessionManager)
    {
        $this->dbAdapter = $dbAdapter;
        $this->authService = $authService;
         $this->sessionManager = $sessionManager;
        //  $this->sessionManager->setSessionManager($this->sessionManager);
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

            // Authentication Adapter
            $authAdapter = new CallbackCheckAdapter(
                $this->dbAdapter,
                'user', // Table name
                'email', // Identity column
                'password', // Credential column
                function ($hash, $password) {
                    return password_verify($password, $hash);
                }
            );

            $authAdapter
                ->setIdentity($data['email'])
                ->setCredential($data['password']);

            // Set adapter to the authentication service
            $this->authService->setAdapter($authAdapter);

            // Attempt authentication
            $result = $this->authService->authenticate();

            if ($result->isValid()) {
                // Fetch the full User entity
                $userTable = new \Laminas\Db\TableGateway\TableGateway('user', $this->dbAdapter);
                $user = $userTable->select(['email' => $data['email']])->current();

                if ($user) {
                    // Store the User entity in the session
                    $session = new Container('user_session');
                    $session->user = $user;

                    // Store the User entity in the AuthenticationService
                    $this->authService->getStorage()->write($user);

                    return $this->redirect()->toRoute('dashboard');
                } else {
                    $viewModel->setVariable('error', 'User not found.');
                }
            } else {
                $viewModel->setVariable('error', 'Invalid credentials.');
            }
        }

        $viewModel->setTemplate('application/auth/login');
        return $viewModel;
    }

    public function registerAction()
    {
        if ($this->authService->hasIdentity()) {
             $_SESSION['islogged'] = true; 

            return $this->redirect()->toRoute('login');
        } 

        $viewModel = new ViewModel();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            // Validate inputs
            if (empty($data['full_name']) || empty($data['email']) || empty($data['phone']) || empty($data['address']) || empty($data['password'])) {
                $viewModel->setVariable('error', 'All fields are required.');
                return $viewModel;
            }

            // Check if email already exists
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('users')
                          ->where(['email' => $data['email']]);
            $statement = $sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();

            if ($result->current()) {
                $viewModel->setVariable('error', 'Email already exists.');
                return $viewModel;
            }

            // Insert user into database
            $insert = $sql->insert('users');
            $insert->values([
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'status' => 'active',
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ]);

            $statement = $sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();

            if ($result->getAffectedRows()) {
                // Successful registration
                return $this->redirect()->toRoute('login');
            } else {
                // Failed registration
                $viewModel->setVariable('error', 'Registration failed.');
            }
        }

        $viewModel->setTemplate('application/auth/register');
        return $viewModel;
    }

    public function logoutAction()
    {
        $this->authService->clearIdentity();
        session_unset();

        return $this->redirect()->toRoute('login');
    }
}