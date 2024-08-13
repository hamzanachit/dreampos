<?php

    namespace Application\Service; 

    use Doctrine\ORM\EntityManager;
    use Application\Entity\Product;
    use Application\Entity\Setting;
    use Picqer\Barcode\BarcodeGeneratorPNG;
class ProductService
{
    protected $entityManager;

    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
    }



    //   public function getProductById($productId) {
    //     try {
    //         $query = $this->entityManager->createQueryBuilder()
    //                 ->from('Application\Entity\Product','o')
    //                 ->select('o.id, o.name, o.category, o.subCategory, o.PriceHt, o.createdBy, o.unit, o.sku, o.minQty, o.quantity, o.description, o.tax, o.discountType, o.price, o.status, o.image, o.createdAt, o.image, o.updatedAt')
    //                 ->Where("o.id = ".$productId);
    //                 // ->andWhere("gf.idUser = ".$id_user);
                    
    //         $data = $query->getQuery()->getResult();
    //         return $data;
    //     } catch (\Throwable $th) {
    //         throw $th;
    //         return [];
    //     }
    // }
  


    //   public function getAllProducts() {
    //     try {
    //         $products = $this->entityManager->getRepository(Product::class)->findAll();
    //         return $products;
    //     } catch (\Throwable $th) {
    //         throw $th;
    //         return null;
    //     }
    // }
    public function getAllProducts($idcompany) {
    try {
        $queryBuilder = $this->entityManager->getRepository(Product::class)->createQueryBuilder('p');
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

    
    
    //  public function getlastSku() {
    //     try {
    //         $sku = $this->entityManager->getRepository(Product::class)->findAll();
    //         return $sku;
    //     } catch (\Throwable $th) {
    //         throw $th;
    //         return $th;
    //     }
    // }

      public function getSku($userid, $companyid){
            try {
                $query = $this->entityManager->createQueryBuilder()
                        ->from('Application\Entity\Setting','s') 
                        ->select('s.skuFormat')
                        ->Where("s.creator = ".$userid)
                        ->AndWhere("s.id = ". $companyid);
                $data = $query->getQuery()->getResult();
                return $data;
            } catch (\Throwable $th) {
                throw $th;
                return null;
            }
        }

         public function AllCategorys($userid){
            try {
                $query = $this->entityManager->createQueryBuilder()
                        ->from('Application\Entity\Category','s') 
                        ->select('s.categoryName,s.id')
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

        
  

        // public function AddProduct($ProductName,$Category,$Minimum,$Quantity,$Tax,$Discount, $Price,$Status,$Image,$SubCategory,
        //                 $PriceHt,$Unit,$creator,$companyid,$Description ,$SKU,$CostPrice){
         
        //     $dateTime = new \DateTime(); 
        //     $dateTime = new \DateTime('2024-07-24 19:47:05');
        //     $product = new Product();
        //     $product->setName($ProductName);
        //     $product->setCategory($Category);
        //     $product->setMinQty($Minimum);
        //     $product->setQuantity($Quantity);
        //     $product->setTax($Tax);
        //     $product->setDiscountType($Discount);
        //     $product->setPrice($Price);
        //     $product->setStatus($Status);
        //     $product->setImage($Image);
        //     $product->setSubcategory($SubCategory);
        //     $product->setPriceHt($PriceHt);
        //     $product->setUnit($Unit);
        //     $product->setSku($SKU);
        //     $product->setIdcompany($companyid);
        //     $product->setCreatedBy($creator);
        //     $product->setCreatedAt($dateTime);
        //     $product->setCostPrice($CostPrice);
        //     $this->entityManager->persist($product);
        //     $this->entityManager->flush();
        //     $this->generateAndSaveBarcode($product->getId(), $SKU);

        //     return $product;

        // }



    public function AddProduct($ProductName, $Category, $Minimum, $Quantity, $Tax, $Discount,$Price, $Status, $Image, $SubCategory, $PriceHt, $Unit, 
    $creator, $companyid, $Description, $SKU, $CostPrice) {
    $dateTime = new \DateTime('2024-07-24 19:47:05');
    $product = new Product();
    $product->setName($ProductName);
    $product->setCategory($Category);
    $product->setMinQty($Minimum);
    $product->setQuantity($Quantity);
    $product->setTax($Tax);
    $product->setDiscountType($Discount);
    $product->setPrice($Price);
    $product->setStatus($Status);
    $product->setImage($Image);
    $product->setSubcategory($SubCategory);
    $product->setPriceHt($PriceHt);
    $product->setUnit($Unit);
    $product->setSku($SKU);
    $product->setIdcompany($companyid);
    $product->setCreatedBy($creator);
    $product->setCreatedAt($dateTime);
    $product->setCostPrice($CostPrice);

    // Persist the product entity
    $this->entityManager->persist($product);
    $this->entityManager->flush();

    // Generate and save the barcode
    $this->generateAndSaveBarcode($product->getId(), $SKU);

    return $product;
}















        public function getProductById($productId){

            $product = $this->entityManager->getRepository(Product::class)->find($productId);
            if ($product === null) {
                throw new \Exception('Product not found');
            }

        return $product;
}

    public function editProduct(                   
        $ProductId,$ProductName,$Category,$Minimum,$Quantity,$Tax,$Discount,$Price,$Status,$Image,$SubCategory,$PriceHt,$Unit,$creator,$companyid,$userid,$Description,$SKU,$CostPrice){
        $product = $this->getProductById($ProductId);
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
        $product->setPriceHt($PriceHt);
        $product->setUnit($Unit);
        $product->setSku($SKU);
        $product->setIdcompany($companyid);
        $product->setCreatedBy($creator);
        $product->setCostPrice($CostPrice);
        $this->entityManager->flush();
        $this->generateAndSaveBarcode($ProductId, $SKU);

        return $product;
    }
    
    private function generateAndSaveBarcode($ProductId, $SKU)
    {
        if (empty($SKU)) {
            $SKU = $this->generateNewSku();
        }
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($SKU, BarcodeGeneratorPNG::TYPE_CODE_128);
        $barcodePath = 'public/img/barcodes/';

        if (!is_dir($barcodePath)) {
            mkdir($barcodePath, 0777, true);
        }

        $barcodeFile = $barcodePath . $ProductId . '.png';
        file_put_contents($barcodeFile, $barcode);

        $product = $this->entityManager->getRepository(Product::class)->find($ProductId);
        $product->setBarcode($barcodeFile);
        $this->entityManager->flush();
    }

    private function generateNewSku()
    {
        return uniqid('SKU-', true);
    }

   public function deleteProduct(int $ProductId): bool{
    $product = $this->entityManager->find(Product::class, $ProductId);
    if($product) {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        return true;
    }
    return false;
}

    public function findProductBySKU($SKU,$companyid){
    //try{
            $query = $this->entityManager->createQueryBuilder()
                ->from('Application\Entity\Product','s') 
                ->select('s')
                ->where('s.sku = :sku')
                ->andWhere('s.idcompany = :idcompany')
                ->setParameter('sku', $SKU)
                ->setParameter('idcompany', $companyid); 
            $data = $query->getQuery()->getResult();
            return $data;
        // } catch (\Throwable $th) {
        //     throw $th;
        //     return null;
        // }
        }
//     public function ($SKU){
//          $product = $this->entityManager->getRepository(Product::class)->find($SKU);
//         return $product;
// }

}