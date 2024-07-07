<?php  
    namespace Application\Service;
    use Doctrine\ORM\EntityManager;
    use Application\Entity\User;

    class UserService{

        protected $entityManager;

        public function __construct(EntityManager $entityManager){

            $this->entityManager = $entityManager;
        }
        
        public function getAllUsers(){
            try {
                $userRepository = $this->entityManager->getRepository(User::class);
                $users = $userRepository->findAll();
                return $users;
            } catch (\Exception $e) { 
                return [];
            }
        }

    public function getUserById($id){
        try {
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->find($id);
            return $user;
        } catch (\Exception $e) { 
            return null;
        }
    }

    public function addUser($userData){
        try {
            $user = new User();
            $user->setFullName($userData['full_name']);
            $user->setEmail($userData['email']);
            $user->setPhone($userData['phone']);
            $user->setAddress($userData['address']);
            $user->setStatus('active');
            $user->setPassword(password_hash($userData['password'], PASSWORD_DEFAULT));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $user->getId();
        } catch (\Exception $e) { 
            return null;
        }
    }

    public function updateUser($id, $userData)
    {
        try {
            $user = $this->entityManager->getRepository(User::class)->find($id);
            if (!$user) {
                throw new \Exception("User with ID $id not found");
            }

            $user->setFullName($userData['full_name']);
            $user->setEmail($userData['email']);
            $user->setPhone($userData['phone']);
            $user->setAddress($userData['address']);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) { 
            return false;
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = $this->entityManager->getRepository(User::class)->find($id);
            
            if (!$user) {
                throw new \Exception("User with ID $id not found");
            }

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            return true;
        } catch (\Exception $e) { 
            return false;
        }
    }
}