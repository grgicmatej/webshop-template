<?php

declare(strict_types=1);

class OrderController
{
    public function index($id = null): void
    {
        $deliveryCost = intval(($this->getSettings()[2])->value);
        $freeDelivery = intval(($this->getSettings()[3])->value);
        $productTotal = ShoppingCart::sum();
        $delivery = intval($productTotal->total) > $freeDelivery ? number_format(0,2) : number_format($deliveryCost, 2);

        $view = new View();
        $view->render('public/checkout',
            [
                'shoppingCart' => ShoppingCart::get(),
                'delivery' => $delivery,
                'orderTotal' => number_format(intval($delivery) + $productTotal->total, 2),
            ]);
    }

    public function order(): void
    {
        $deliveryCost = intval(($this->getSettings()[2])->value);
        $freeDelivery = intval(($this->getSettings()[3])->value);
        $productsTotal = ShoppingCart::sum();
        $deliveryTotal = intval($productsTotal->total) > $freeDelivery ? number_format(0,2) : number_format($deliveryCost, 2);
        $total = number_format(intval($deliveryTotal) + $productsTotal->total, 2);
        $orderId = ShoppingCart::getOrderId()['order_id'];
        ShoppingCart::updateOrderStatus($orderId);
        Order::newOrder($orderId, floatval($productsTotal->total), floatval($deliveryTotal), floatval($total));
        Users::updateUser();
        Users::updateUserDetails();
        //@todo header na success
        var_dump("prošlo sve");
        die();
    }

    private function getSettings(): array
    {
        return Settings::all();
    }
}