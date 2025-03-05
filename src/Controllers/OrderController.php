<?php
namespace Controllers;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;


class OrderController extends BaseController
{
    private  Product $productModel;
    private OrderProduct $orderProductModel;
    private UserProduct $userProductModel;
    private Order $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
        $this->orderModel = new Order();
    }
    public function getCheckOutForm()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $orderProducts = $this->userProductModel->getAllByUserId($user->getId());
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
        if (!$this->authService->check()) {
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
                $productId = $userProduct->getProductId();
                $amount = $userProduct->getAmount();
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
        if (!$this->authService->check()) {
            header("Location: /login");
            exit();
        }
        $user = $this->authService->getCurrentUser();
        $userOrders = $this->orderModel->getAllByUserId($user->getId());

        $newUserOrders = [];

        foreach ($userOrders as $userOrder) {

            $orderProducts = $this ->orderProductModel->getAllByOrderId($userOrder->getId());
            $newOrderProducts = [];
            $sum = 0;

            foreach ($orderProducts as $orderProduct) {

                $product = $this->productModel->getOneById($orderProduct->getProductId());
                $orderProduct->setProduct($product);
                $totalSum = $orderProduct->getAmount() * $product->getPrice();
                $orderProduct->getProduct()->setTotalSum($totalSum);
                $sum+= $totalSum;
                $newOrderProducts[] = $orderProduct;
            }

            $userOrder->setTotal($sum);

            $userOrder->setProducts($newOrderProducts);
            $newUserOrders[] = $userOrder;
        }
        require_once '../Views/order_detail.php';
    }


    private function validateOrder(array $data): array
    {
        $errors = [];
        if (isset($data['name'])) {
            if (strlen($data['name']) < 3) {
                $errors['name'] = 'Имя пользователя должно быть больше 3 символов';
            } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $data['name'])) {
                $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
            }
        } else {
            $errors['name'] = "Имя должно быть заполнено";
        }

        if (isset($data['address'])) {
            if (!preg_match('/^[\d\s\w\.,-]+$/u', $data['address'])) {
                $errors['address'] = "Адрес содержит недопустимые символы";
            }elseif (!preg_match('/[а-яА-ЯёЁ]+\s+\d+/', $data['address'])) {
                $errors['address'] = "Укажите номер дома и улицу";
            }
        } else {
            $errors['address'] = "Address должен быть заполнен";
        }
        if (isset($data['phone'])) {
            $cleanedPhone = preg_replace('/\D/', '', $data['phone']);
            if(strlen($cleanedPhone) < 11) {
                $errors['phone'] = 'Номер телефона не может быть меньше 11 символов';
            }elseif (!preg_match('/^\+\d+$/', $data['phone'])) {
                $errors['phone'] = "+ и цифры после";
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
            $product = $this->productModel->getOneById($orderProduct->getProductId());
            $orderProduct->setProduct($product);
            $totalSum = $orderProduct->getAmount() * $product->getPrice();
            $orderProduct->getProduct()->setTotalSum($totalSum);
            $newOrderProducts[] = $orderProduct;

        }
        return $newOrderProducts;
    }
    public function totalOrderProducts(array $newOrderProducts):int
    {
        $total = 0;
        foreach ($newOrderProducts as $newOrderProduct) {
           $total += $newOrderProduct->getProduct()->getTotalSum();
        }
        return $total;
    }
}

