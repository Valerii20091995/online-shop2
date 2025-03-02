<?php
namespace Model;
class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $image_url;
    private $totalSum;
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
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $product = $stmt->fetch();
        return $this->createObject($product);
    }
    public function getByCatalog(int $userId):array|false
    {
        $stmt = $this->pdo->query('SELECT * FROM products');
        $products = $stmt->fetchAll();
        $newProducts = [];
        foreach ($products as $product) {
            $newProducts[] = $this->createObject($product);
        }
        return $newProducts;

    }

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function getImageUrl()
    {
        return $this->image_url;
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