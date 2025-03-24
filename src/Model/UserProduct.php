<?php
namespace Model;
class UserProduct extends Model
{
    private int $id;
    private int $user_id;
    private int $product_id;
    private int $amount;
    private Product $product;
    private int $totalSum;
    protected static function getTableName():string
    {
        return "user_products";
    }
    public static function createObject($userProduct):self|null
    {
        if(!$userProduct)
        {
            return null;
        }
        $object = new self();
        $object->id = $userProduct['id'];
        $object->user_id = $userProduct['user_id'];
        $object->product_id = $userProduct['product_id'];
        $object->amount = $userProduct['amount'];

        $productData = [
            "id" => $userProduct['product_id'],
            "name" => $userProduct['name'],
            "description" => $userProduct['description'],
            "price" => $userProduct['price'],
            "image_url" => $userProduct['image_url'],
        ];
        $product = Product::createObject($productData);
        $object->setProduct($product);
        $object->setTotalSum($object->getAmount() * $product->getPrice());
        return $object;
    }
    public static function getByUserProducts(int $userId, int $productId):self|null
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("SELECT *FROM $tableName up INNER JOIN products p ON up.product_id = p.id WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $product = $stmt->fetch();
        if(!$product)
            return null;
        return self::createObject($product);


    }
    public static function addProductByUser(int $userId, int $productId, int $amount):void
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("INSERT INTO $tableName (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $amount]);
    }
    public static function updateProductByUser(int $amount, int $productId, int $userId):array|false
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("UPDATE $tableName SET amount = :amount WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['amount' => $amount, 'productId' => $productId, 'userId' => $userId]);
//        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
//        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $result = $stmt->fetch();
        return $result;


    }
    public static function getAllByUserIdWithProducts(int $userId):array|false
    {
        $tableName = self::getTableName();
        $stmt = static::getPDO()->prepare("SELECT * FROM $tableName up INNER JOIN products p ON up.product_id = p.id WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $UserProducts = $stmt->fetchAll();
        $newUserProducts =[];
        foreach ($UserProducts as $UserProduct) {
            $newUserProducts[] = static::createObject($UserProduct);
        }
        return $newUserProducts;

    }
    public static function deleteOrder(int $userId)
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("DELETE FROM $tableName WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
    }
    public static function removeProductInCart($productId, $userId)
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("DELETE FROM $tableName WHERE product_id = :productId AND user_id = :userId");
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
    public function getTotalSum():int
    {
        return $this->totalSum;
    }
    public function setTotalSum($totalSum): void
    {
        $this->totalSum = $totalSum;
    }




}