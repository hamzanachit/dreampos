<?php
    namespace Application\Service; 

    use Doctrine\ORM\EntityManager;
     use Application\Entity\Company;
     use Application\Entity\User;

class CompanyService{
    protected $entityManager;

    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
    }




   public function getCheckCompanyById($userid){ 
    $setting = $this->entityManager->getRepository(Setting::class)->findOneBy(['creator' => $userid]);
    
    if ($setting === null) {
        return null; // Return null if no setting found for the email
    } else {
        return $setting; // Return the found Setting entity
    }



}
}