<?php

    namespace Application\Service; 

    use Doctrine\ORM\EntityManager;
    use Application\Entity\Setting;
    use Application\Entity\User;
    use Application\Entity\Category;
    use Application\Entity\SubCategory;
    use Application\Entity\Company; 

    class SalesService{
        
        protected $entityManager;

    public function __construct(EntityManager $entityManager){
            $this->entityManager = $entityManager;
    }


    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getSalesService($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveSalesService(SalesService $SalesService)
    {
        $data = [
            'order_number' => $SalesService->order_number,
            'customer_id' => $SalesService->customer_id,
            'order_date' => $SalesService->order_date,
            'total_amount' => $SalesService->total_amount,
            'status' => $SalesService->status,
        ];

        $id = (int) $SalesService->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getSalesService($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update sales order with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteSalesService($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}