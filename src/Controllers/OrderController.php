<?php
namespace Controllers;
use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;
use Request\OrderRequest;
use Service\OrderService;
use Model\User;


class OrderController extends BaseController
{
    private  Product $productModel;
    private OrderProduct $orderProductModel;
    private UserProduct $userProductModel;
    private Order $orderModel;
    private OrderService $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
        $this->orderModel = new Order();
        $this->orderService = new OrderService();
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
    public function handleCheckOut(OrderRequest $request)
    {
        if (!$user = $this->authService->getCurrentUser()) {
            header("Location: /login");
            exit();
        }
        $errors = $request->validate();
        if (empty($errors)) {
            $dto = new OrderCreateDTO($request->getName(), $request->getPhone(),$request->getComment(),$request->getAddress(),$user);
            $this->orderService->handleCheckOut($dto);
            header('Location: /orders');
            exit();
        } else {
            require_once '../Views/order_form.php';
        }
    }
    public function getAllOrders()
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

