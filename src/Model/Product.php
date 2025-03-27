<?php
namespace Model;
class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $image_url;

    private int $amountInCart;
    protected static function getTableName():string
    {
        return "products";
    }
    public function __construct(string $name, int $price)
    {
        $this->name = $name;
        $this->price = $price;
    }
    public static function createObject(array $product):self|null
    {
        if(!$product) {
            return null;
        }
        $object = new self($product['name'], intval($product['price']));
        $object->id = $product['id'];
        $object->name = $product['name'];
        $object->description = $product['description'];
        $object->price = (int)$product['price'];
        $object->image_url = $product['image_url'];
        return $object;
    }

    public static function getOneById($productId):self|null
    {
        $tableName = self::getTableName();
        $stmt = static::getPDO()->prepare("SELECT * FROM $tableName WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $product = $stmt->fetch();
        return static::createObject($product);
    }
    public static function getByCatalog(int $userId):array|false
    {
        $tableName = self::getTableName();
        $stmt = static::getPDO()->query("SELECT * FROM $tableName");
        $products = $stmt->fetchAll();
        $newProducts = [];
        foreach ($products as $product) {
            $newProducts[] = static::createObject($product);
        }
        return $newProducts;

    }

    public function getId():int
    {
        return $this->id;
    }
    public function getName():string
    {
        return $this->name;
    }
    public function getDescription():string
    {
        return $this->description;
    }
    public function getPrice():int
    {
        return $this->price;
    }
    public function getImageUrl():string
    {
        return $this->image_url;
    }

    public function getAmountInCart()
    {
        return $this->amountInCart;
    }
    public function setAmountInCart($amountInCart): void
    {
        $this->amountInCart = $amountInCart;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

}