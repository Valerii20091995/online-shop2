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
    protected function getTableName():string
    {
        return "products";
    }
    private function createObject(array $product):self|null
    {
        if(!$product) {
            return null;
        }
        $object = new self();
        $object->id = $product['id'];
        $object->name = $product['name'];
        $object->description = $product['description'];
        $object->price = $product['price'];
        $object->image_url = $product['image_url'];
        return $object;
    }

    public function getOneById($productId):self|null
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $product = $stmt->fetch();
        return $this->createObject($product);
    }
    public function getByCatalog(int $userId):array|false
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->getTableName()}");
        $products = $stmt->fetchAll();
        $newProducts = [];
        foreach ($products as $product) {
            $newProducts[] = $this->createObject($product);
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

}