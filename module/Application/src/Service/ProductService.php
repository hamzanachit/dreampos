<?php

    namespace Application\Service; 

    use Doctrine\ORM\EntityManager;
    use Application\Entity\Product;
    use Application\Entity\Setting;

class ProductService
{
    protected $entityManager;

    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
    }



    //   public function getListCompanies($id_user) {
    //     try {
    //         $query = $this->entityManager->createQueryBuilder()
    //                 ->from('Application\Entity\Companybayard','o')
    //                 ->innerJoin("Application\Entity\GroupCompany", "gc", "WITH", "gc.idcompany = o.idCompany")
    //                 ->innerJoin("Application\Entity\Groupe", "g", "WITH", "g.idgroup = gc.idgroup")
    //                 ->innerJoin("Application\Entity\GroupFavoris", "gf", "WITH", "gf.idGroup = g.idgroup")
    //                 ->leftJoin("Application\Entity\Country", "t", "WITH", "t.idCountry = o.idCountry")
    //                 ->select('o.idCompany, o.companyCode, o.name')
    //                 ->addSelect('t.description AS Country')
    //                 ->addSelect('gf.idUser AS idUser')
    //                 ->where("o.name <> '' ")
    //                 ->andWhere("o.archive = 1")
    //                 ->andWhere("o.entitystatus != 7")
    //                 ->andWhere("gf.idUser = ".$id_user)
    //                 ->orderby("o.companyCode","ASC");
                    
    //         $data = $query->getQuery()->getResult();
    //         return $data;
    //     } catch (\Throwable $th) {
    //         throw $th;
    //         return [];
    //     }
    // }
  


      public function getAllProducts() {
        try {
            $products = $this->entityManager->getRepository(Product::class)->findAll();
            return $products;
        } catch (\Throwable $th) {
            throw $th;
            return null;
        }
    }
    
    
    //  public function getlastSku() {
    //     try {
    //         $sku = $this->entityManager->getRepository(Product::class)->findAll();
    //         return $sku;
    //     } catch (\Throwable $th) {
    //         throw $th;
    //         return $th;
    //     }
    // }

      public function getSku($userid){
            try {
                $query = $this->entityManager->createQueryBuilder()
                        ->from('Application\Entity\Setting','s') 
                        ->select('s.skuFormat')
                        ->Where("s.creator = ".$userid);
                        // ->AndWhere("s.id = ".$idcompany);
                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return null;
            }
        }

   public function getLastProductSku(){
        try {
            $query = $this->entityManager->createQueryBuilder()
                ->select('p.sku')
                ->from('Application\Entity\Product', 'p')
                // ->where('p.creator = :userId')
                ->orderBy('p.id', 'DESC')  // Order by primary key or any timestamp field to get the latest entry
                ->setMaxResults(1);  // Limit the result to 1
                // ->setParameter('userId', $userId);

            $data = $query->getQuery()->getOneOrNullResult();  // Use getOneOrNullResult to fetch a single result or null
            return $data;
        } catch (\Throwable $th) {
            throw $th;
            return null;
        }
    }

        
  

        public function AddProduct($ProductName,$Category,$Description,$Minimum,$Quantity,$Tax,$Discount,$Price,$Status,$Image,$SubCategory,$Brand,$Unit,$SKU){
            
            $product = new Product();
            $product->setName($ProductName);
            $product->setCategory($Category);
            $product->setDescription($Description); 
            $product->setMinQty($Minimum);
            $product->setQuantity($Quantity);
            $product->setTax($Tax);
            $product->setDiscountType($Discount);
            $product->setPrice($Price);
            $product->setStatus($Status);
            $product->setImage($Image);
            $product->setSubcategory($SubCategory);
            $product->setBrand($Brand);
            $product->setUnit($Unit);
            $product->setSku($SKU);
            $product->setCreatedBy("Hamza nachite");
            
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            return $product;

        }

        public function getProductById($id){

            $product = $this->entityManager->getRepository(Product::class)->find($id);
            if ($product === null) {
                throw new \Exception('Product not found');
            }

        return $product;
}

     
}