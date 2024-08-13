<?php
    namespace Application\Service; 

    use Doctrine\ORM\EntityManager;
     use Application\Entity\Company;
     use Application\Entity\Customers;

class CustomersService{
    protected $entityManager;

    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
    }
   public function getAllCustomers($idcompany) {
    try {
        $queryBuilder = $this->entityManager->getRepository(Customers::class)->createQueryBuilder('p');
        $queryBuilder->where('p.idcompany = :idcompany')
                    //  ->andWhere('p.iduser = :iduser')
                     ->setParameter('idcompany', $idcompany);
                    //  ->setParameter('iduser', $iduser);

        $products = $queryBuilder->getQuery()->getResult();
        return $products;
    } catch (\Throwable $th) {
        throw $th;
        return null;
    }
}

 
    public function AddCustomer($name, $email, $phone, $ICE, $address, $bank, $note, $userId, $idCompany) {
            $dateTime = new \DateTime('2024-07-24 19:47:05');
            $Customers = new Customers();
            $Customers->setName($name);
            $Customers->setEmail($email);
            $Customers->setPhone($phone);
            $Customers->setAddress($address);
            $Customers->setBank($bank);
            $Customers->setNote($note);
            $Customers->setIdcompany($idCompany);
            $Customers->setCreatedBy($userId);
            $Customers->setCreatedAt($dateTime);
            $Customers->setICE($ICE);
        $this->entityManager->persist($Customers);
        $this->entityManager->flush();
    return $Customers;
}

 public function editCustomer($id, $name, $email, $phone, $ICE, $address, $bank, $note, $userId, $idCompany, $image = null) {
    // Retrieve the customer entity by its ID
    $customer = $this->entityManager->getRepository(Customers::class)->find($id);
    
    if (!$customer) {
        throw new \Exception('Customer not found.');
    }
            $dateTime = new \DateTime('2024-07-24 19:47:05');

    // Update the customer properties
    $customer->setName($name);
    $customer->setEmail($email);
    $customer->setPhone($phone);
    $customer->setAddress($address);
    $customer->setBank($bank);
    $customer->setNote($note);
    $customer->setIdcompany($idCompany);
    $customer->setICE($ICE);
    $customer->setUpdatedBy($userId);
    $customer->setUpdatedAt($dateTime); // Set current date and time for update

    // Optional: Handle image update if provided
    if ($image) {
        $customer->setImage($image); // Assuming you have a method for handling the image
    }

    // Persist the changes to the database
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $customer;
    }



 public function deleteCustomer($id){
    $Customer = $this->entityManager->find(Customers::class, $id);
    if($Customer) {
        $this->entityManager->remove($Customer);
        $this->entityManager->flush();
        return true;
    }
    return false;
}












    }