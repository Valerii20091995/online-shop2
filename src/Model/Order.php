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
    private int $sum;
    private array $products =[];

    private array $orderProducts = [];
    protected static function getTableName():string
    {
        return "orders";
    }
    public static function createObject(array $userOrder):self|null
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
    public static function addOrder(string $name, string $phone, string $comment, string $address, int $userId):int|false
    {
        $tableName = self::getTableName();
        $stmt = static::getPDO()->prepare(
            "INSERT INTO $tableName (name, phone, comment, address, user_Id) 
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
    public static function getAllByUserId(int $userId):array|false
    {
        $tableName = self::getTableName();
        $orderProductsTableName = OrderProduct::getTableName();
        $productsTable = Product::getTableName();

        $stmt = static::getPDO()->prepare(
            "SELECT o.*, op.product_id, op.amount, p.name AS product_name, p.price
        FROM $tableName o
        INNER JOIN $orderProductsTableName op ON op.order_id = o.id
        INNER JOIN $productsTable p ON op.product_id = p.id
        WHERE o.user_id = :userId"
        );
        $stmt->execute([':userId' => $userId]);
        $orderData = $stmt->fetchAll();

        $orders = [];
        foreach ($orderData as $order) {
            // Проверяем, если заказ уже существует в массиве
            if (!isset($orders[$order['id']])) {
                // Создаем новый объект Order
                $orders[$order['id']] = static::createObject($order);
                $orders[$order['id']]->orderProducts = [];
                // Инициализируем массив для хранения продуктов
            }
            // Создаем объект для каждого товара в заказе
            $orderProduct = new OrderProduct();
            $orderProduct->setProductId($order['product_id']);
            $orderProduct->setAmount($order['amount']);
            $product = new Product($order['product_name'], intval($order['price']));
            $orderProduct->setProduct($product);

            // Добавляем товар в массив товаров заказа
            $orders[$order['id']]->orderProducts[] = $orderProduct;

        }
        return array_values($orders);
    }
    public function getOrderProducts():array
    {
        return $this->orderProducts;
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

    public function getSum(): int
    {
        return $this->sum;
    }

    public function setSum(int $sum): void
    {
        $this->sum = $sum;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

//    public function getOrderProducts(): array
//    {
//        return $this->orderProducts;
//    }

    public function setOrderProducts(array $orderProducts): void
    {
        $this->orderProducts = $orderProducts;
    }







}