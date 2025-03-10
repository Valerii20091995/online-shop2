<?php

namespace Model;

class Order extends Model
{
    private int $id;
    private string $name;
    private string $phone;
    private string $comment;
    private string $address;
    private int $user_id;
    private Product $product;
    private int $total;
    private array $products =[];
    protected function getTableName():string
    {
        return "orders";
    }
    private function createObject(array $userOrder):self|null
    {
        if(!$userOrder) {
            return null;
        }
        $object = new self();
        $object->id = $userOrder['id'];
        $object->name = $userOrder['name'];
        $object->phone = $userOrder['phone'];
        $object->comment = $userOrder['comment'];
        $object->address = $userOrder['address'];
        $object->user_id = $userOrder['user_id'];
        return $object;

    }
    public function addOrder(string $name, string $phone, string $comment, string $address, int $userId):int|false
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->getTableName()} (name, phone, comment, address, user_Id) 
                   VALUES (:name, :phone, :comment, :address, :user_id) RETURNING id");
        $stmt->execute([
            ':name' => $name,
            ':phone' => $phone,
            ':comment' => $comment,
            ':address' => $address,
            ':user_id' => $userId
        ]);
        $data = $stmt->fetch();

        return $data['id'];
    }
    public function getAllByUserId(int $userId):array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :userId");
        $stmt->execute([':userId' => $userId]);
         $userOrders = $stmt->fetchAll();
         $newUserOrders = [];
         foreach ($userOrders as $userOrder) {
             $newUserOrders[] = $this->createObject($userOrder);
         }
         return $newUserOrders;

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getUserId(): int
    {
        return $this->user_id;
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

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $products): void
    {
        $this->products = $products;
    }







}