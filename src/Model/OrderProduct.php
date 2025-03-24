<?php

namespace Model;

class OrderProduct extends Model
{
    private int $id;
    private int $order_id;
    private int $product_id;
    private int $amount;
    private Product $product;
    private int $sum;
    protected static function getTableName():string
    {
        return "order_products";
    }
    public static function createObject($orderProduct):self|null
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

    public static function create(int $orderId, int $productId,int $amount)
    {
        $tableName = self::getTableName();
        $stmt = static::getPDO()->prepare(
            "INSERT INTO $tableName (order_id, product_id, amount) 
             VALUES (:orderId, :productId, :amount)");
        $stmt->execute(['orderId' => $orderId, 'productId' => $productId, 'amount' => $amount]);
    }
    public static function getAllByOrderId(int $orderId):array|false
    {
        $tableName = self::getTableName();
        $productsTable = Product::getTableName();
        $stmt = static::getPDO()->prepare(
            "SELECT op.*, p.name as product_name, p.price, p.image
            FROM $tableName op
            INNER JOIN $productsTable p ON op.product_id = p.id
            WHERE op.order_id = :orderId"
        );
        $stmt->execute(['orderId' => $orderId]);
        $orderProducts = $stmt->fetchAll();
        $result = [];
        foreach ($orderProducts as $orderProduct) {
            $result[] = self::createObject($orderProduct);
        }
        return $result;
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

    public function getSum (): int
    {
        return $this->sum;
    }

    public function setSum(int $sum): void
    {
        $this->sum = $sum;
    }




}