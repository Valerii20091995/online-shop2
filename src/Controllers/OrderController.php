<?php
namespace Controllers;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;


class OrderController
{
    private  Product $productModel;
    private OrderProduct $orderProductModel;
    private UserProduct $userProductModel;
    private Order $orderModel;

    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
        $this->orderModel = new Order();
    }
    public function getCheckOutForm()
    {
        if(session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $orderProducts = $this->userProductModel->getAllByUserId($userId);
            if (empty($orderProducts)) {
                header('Location: /catalog');
                exit();
            }
            $newOrderProducts = $this->newOrderProducts($orderProducts);
            $total = $this->totalOrderProducts($newOrderProducts);
            require_once './../Views/order_form.php';
        } else {
            header('Location: /login');
            exit();
        }
    }
    public function handleCheckOut()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit();
        }
        $errors = $this->validateOrder($_POST);
        if (empty($errors)) {
            $userId = $_SESSION['userId'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $comment = $_POST['comment'];
            $address = $_POST['address'];

            $orderId = $this->orderModel->addOrder($name, $phone, $comment, $address, $userId);
            $userProducts = $this->userProductModel->getAllByUserId($userId);

            foreach ($userProducts as $userProduct) {
                $productId = $userProduct['product_id'];
                $amount = $userProduct['amount'];
                $this->orderProductModel->create($orderId, $productId, $amount);
            }
            $this->userProductModel->deleteOrder($userId);
            header('Location: /orders');
            exit();
        } else {
            require_once '../Views/order_form.php';
        }
    }
    public function getAllOrders(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit();
        }
        $userId = $_SESSION['userId'];
//      теперь мы идем в бд и вытаскиваем в таблице заказов данные по сесии
        $userOrders = $this->orderModel->getAllByUserId($userId);
//        $userOrders = [
//            [
//                'id'=> 1,
//                'name' => 'tima',
//                'phone' = '12322',
//                'comment' = 'qq',
//                'user_id' = 1,
//                'address' = 'tima'
//            ],
//            [
//                'id'=> 2,
//                'name' => 'tima1',
//                'phone' = '123221',
//                'comment' = 'qq1',
//                'user_id' = 1,
//                'address' = 'tima1'
//            ],
//        ];
        $newUserOrders = [];
//        через цикл вытаскиваем каждый заказ и отдельно
        foreach ($userOrders as $userOrder) {
//            $userOrder = [
//                'id'=> 1,
//                'name' => 'tima',
//                'phone' = '12322',
//                'comment' = 'qq',
//                'user_id' = 1,
//                'address' = 'tima'
//            ];
//            далее идем в бд к таблице order_product чтобы получить данные используя индефикатор из таблицы Orders
            $orderProducts = $this ->orderProductModel->getAllByOrderId($userOrder['id']);
//            $orderProducts = [
//              [
//                  'id =>1 ',
//                  'order_id => 1',
//                  'product_id => 1',
//                  'amount' => 100
//              ],
//                [
//                   'id'=> 2,
//                   'order_id => 1',
//                   'product_id' => 2,
//                   'amount' => 1000
//                ],
//
//            ];
//            теперь сохраняем копию этого массива нового хранилище для дальшейщих действий в $newOrderProducts
            $newOrderProducts = [];
//            переменная сам для итоговой цены за заказ для покупателя
            $sum = 0;
            foreach ($orderProducts as $orderProduct) {
//                $orderProduct =[
//                    'id'=> 2,
//                   'order_id => 1',
//                   'product_id' => 2,
//                   'amount' => 1000
//                ];
//                дальше идем в бд чтобы вытащить информацию о продукте
                $product = $this->productModel->getOneById($orderProduct['product_id']);
//                $product =[
//                    'id' => 1,
//                    'name' => 'moloko',
//                    'price' => 100,
//                    'image_url' =>'test.jpg',
//                    'description' => 'vkys'
//                ];
//                здесь мы сохраняем значения $product v $orderProduct и таким образом наш массив добавилисб новые данные

                $orderProduct['name'] = $product['name'];
                $orderProduct['price'] = $product['price'];
//                далее нужна итоговая сумма наших продуктов и добавляем новый ключ
                $orderProduct['totalSum'] = $orderProduct['amount'] * $orderProduct['price'];
//                $orderProduct = [
//                    'id' => 2,
//                    'order_id => 1',
//                    'product_id' => 2,
//                    'amount' => 1000,
//                    'name' => 'moloko',
//                    'price' => 100,
//                    'totalSum' => 1000,
//                ];
//                теперь чтобы не потерять наш массив который выше указан мы пересохраняем его в новый массив чтобы потом использовать
                $newOrderProducts[] = $orderProduct;
//                теперь ниже достаем уже известную нам сумму итоговую за товар в количестве в тоталсам
                $sum =$orderProduct['totalSum'] + $sum;
            }
//            $newOrderProducts = $this->newOrderProducts($orderProducts);
//            после цикла теперь итоговую сумму заказ сохраняем в новый ключ массива userorder
            $userOrder['total'] = $sum;
//            здесь облегчаем просто для себя сохраняем в ключ продуктс всю информацию о продукте чтобы потом к ней обратиться в разметке
            $userOrder['products'] = $newOrderProducts;
            $newUserOrders[] = $userOrder;


        }
        require_once '../Views/order_detail.php';
    }


    private function validateOrder(array $data): array
    {
        $errors = [];
        if (isset($data['name'])) {
            $name = $data['name'];
            if (strlen($name) < 3) {
                $errors['name'] = "Имя не может содержать меньше 3 символов";
            }
        } else {
            $errors['name'] = "Имя должно быть заполнено";
        }

        if (isset($data['address'])) {
            $address = $data['address'];
            if (strlen($address) < 5) {
                $errors['address'] = "Неккоректный адрес(не меньше 5 символов)";
            }
        } else {
            $errors['address'] = "Address должен быть заполнен";
        }
        if (isset($data['phone'])) {
            $phone = $data['phone'];
            if (strlen($phone) < 12) {
                $errors['phone'] = "Введите корректный номер телефона";
            }
        } else {
            $errors['phone'] = "Поле Phone должно быть заполнено";
        }
        return $errors;
    }
    public function newOrderProducts(array $orderProducts)
    {
        $newOrderProducts = [];
        $sum = 0;
        foreach ($orderProducts as $orderProduct) {
            $product = $this->productModel->getOneById($orderProduct['product_id']);
            $orderProduct['name'] = $product['name'];
            $orderProduct['price'] = $product['price'];
            $orderProduct['totalSum'] = $orderProduct['amount'] * $orderProduct['price'];
            $newOrderProducts[] = $orderProduct;
            $sum =$orderProduct['totalSum'] + $sum;
        }
        return $newOrderProducts;
    }
    public function totalOrderProducts(array $newOrderProducts):int
    {
        $total = 0;
        foreach ($newOrderProducts as $newOrderProduct) {
           $total += $newOrderProduct['totalSum'];
        }
        return $total;
    }
}

