<?php
namespace Model;
class UserProduct extends Model
{
    private int $id;
    private int $user_id;
    private int $product_id;
    private int $amount;
    private Product $product;
    protected function getTableName():string
    {
        return "user_products";
    }
    private function createObject($product):self|null
    {
        if(!$product)
        {
            return null;
        }
        $object = new self();
        $object->id = $product['id'];
        $object->user_id = $product['user_id'];
        $object->product_id = $product['product_id'];
        $object->amount = $product['amount'];
        return $object;
    }
    public function getByUserProducts(int $userId, int $productId):self|null
    {
        $stmt = $this->pdo->prepare("SELECT *FROM {$this->getTableName()} WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $product = $stmt->fetch();
        if(!$product)
            return null;
        return $this->createObject($product);


    }
    public function addProductByUser(int $userId, int $productId, int $amount):void
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->getTableName()} (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $amount]);
    }
    public function updateProductByUser(int $amount, int $productId, int $userId):array|false
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->getTableName()} SET amount = :amount WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['amount' => $amount, 'productId' => $productId, 'userId' => $userId]);
//        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
//        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $result = $stmt->fetch();
        return $result;


    }
    public function getAllByUserId(int $userId):array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $UserProducts = $stmt->fetchAll();
        $newUserProducts =[];
        foreach ($UserProducts as $UserProduct) {
            $newUserProducts[] = $this->createObject($UserProduct);
        }
        return $newUserProducts;

    }
    public function deleteOrder(int $userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->getTableName()} WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
    }
    public function removeProductInCart($productId, $userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->getTableName()} WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }




}