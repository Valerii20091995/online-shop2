<?php

namespace Model;

class OrderProduct extends Model
{
    private int $id;
    private int $order_id;
    private int $product_id;
    private int $amount;
    private Product $product;
    private int $total;
    protected function getTableName():string
    {
        return "order_products";
    }
    private function createObject($orderProduct):self|null
    {
        if(!$orderProduct){
            return null;
        }
        $object = new self();
        $object->id = $orderProduct['id'];
        $object->order_id = $orderProduct['order_id'];
        $object->product_id = $orderProduct['product_id'];
        $object->amount = $orderProduct['amount'];
        return $object;
    }

    public function create(int $orderId, int $productId,int $amount)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->getTableName()} (order_id, product_id, amount) 
             VALUES (:orderId, :productId, :amount)");
        $stmt->execute(['orderId' => $orderId, 'productId' => $productId, 'amount' => $amount]);
    }
    public function getAllByOrderId(int $orderId):array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->getTableName()} WHERE order_id = :orderId"
        );
        $stmt->execute(['orderId' => $orderId]);
        $orderProducts = $stmt->fetchAll();
        $newOrderProducts = [];
        foreach ($orderProducts as $orderProduct) {
            $newOrderProducts[] = $this->createObject($orderProduct);
        }
        return $newOrderProducts;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->order_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }




}