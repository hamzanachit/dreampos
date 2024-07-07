<?php 
// module/Application/src/Controller/UserController.php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Application\Service\UserService;
use Laminas\ServiceManager\ServiceManager;
// use Application\Controller\ViewModel;
use Laminas\View\Model\ViewModel;

 
class UserController extends AbstractActionController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function indexAction()
    {
        $users = $this->userService->getAllUsers();
// dd( $users);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('Application/user/index');

        return  $viewModel;
    }

    public function viewAction()
    {
        $id = $this->params()->fromRoute('id');
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return new JsonModel(['error' => 'User not found']);
        }

        return new JsonModel(['user' => $user]);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $data = $request->getPost()->toArray();

        $userId = $this->userService->addUser($data);

        if (!$userId) {
            return new JsonModel(['error' => 'Failed to create user']);
        }

        return new JsonModel(['user_id' => $userId]);
    }

    public function updateAction()
    {
        $id = $this->params()->fromRoute('id');
        $request = $this->getRequest();
        $data = $request->getPost()->toArray();

        $success = $this->userService->updateUser($id, $data);

        if (!$success) {
            return new JsonModel(['error' => 'Failed to update user']);
        }

        return new JsonModel(['success' => true]);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        $success = $this->userService->deleteUser($id);

        if (!$success) {
            return new JsonModel(['error' => 'Failed to delete user']);
        }

        return new JsonModel(['success' => true]);
    }
}
