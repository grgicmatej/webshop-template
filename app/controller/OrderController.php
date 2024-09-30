<?php

declare(strict_types=1);

use Enum\OrdersEnum;
use Enum\SettingKeyEnum;
use Model\SettingModel;
use Validator\OrderValidator;
use Validator\UserDetailsValidator;
use Validator\UserValidator;

class OrderController extends SecurityController
{
    public function index(?string $id = null): void
    {
        $deliveryCost = floatval(($this->getSettingByKey(SettingKeyEnum::getDeliveryCostKey()))->getvalue());

        $freeDeliveryEnabled = $this->getSettingByKey(SettingKeyEnum::getActiveDeliveryFreeKey());
        $productTotal = ShoppingCart::sum();
        $delivery = number_format($deliveryCost, 2);
        if (SettingKeyEnum::getEnabledFreeDelivery() === $freeDeliveryEnabled->getValue()) {
            $freeDelivery = intval(($this->getSettingByKey(SettingKeyEnum::getDeliveryFreeKey()))->getvalue());
            $delivery = intval($productTotal->getSum()) > $freeDelivery ? number_format(0,2) : number_format($deliveryCost, 2);
        }

        $view = new View();
        $view->render('public/checkout',
            [
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'delivery' => $delivery,
                'orderTotal' => number_format(floatval($delivery) + floatval($productTotal->getSum()), 2),
                'orderTotalWithoutDelivery' => number_format(floatval($productTotal->getSum()), 2)
            ]);
    }

    public function order(): void
    {
        $deliveryCost = floatval(($this->getSettingByKey(SettingKeyEnum::getDeliveryCostKey()))->getvalue());

        $freeDeliveryEnabled = $this->getSettingByKey(SettingKeyEnum::getActiveDeliveryFreeKey());
        $productTotal = ShoppingCart::sum();
        $delivery = number_format($deliveryCost, 2);
        if (SettingKeyEnum::getEnabledFreeDelivery() === $freeDeliveryEnabled->getValue()) {
            $freeDelivery = intval(($this->getSettingByKey(SettingKeyEnum::getDeliveryFreeKey()))->getvalue());
            $delivery = intval($productTotal->getSum()) > $freeDelivery ? number_format(0,2) : number_format($deliveryCost, 2);
        }

        if (OrdersEnum::getPickup() === $_POST['payment_method']) {
            $delivery = floatval('0.00');
        }

        $total = number_format(intval($delivery) + floatval($productTotal->getSum()), 2);
        $shoppingCart = ShoppingCart::getShoppingCart(Users::get());
        ShoppingCart::updateOrderStatus($shoppingCart->getOrder());
        $order = OrderValidator::generateNewOrder($shoppingCart, floatval($productTotal->getSum()), floatval($delivery), floatval($total));
        Order::newOrder($order);
        $finalizedOrder = Order::getByOrderId($order);
        $user = UserValidator::generateFromRequest();
        if (null === Users::findUser($user->getEmail())) {
            Users::updateUser($user);
        }
        $userDetails = UserDetailsValidator::generateFromRequest($user);
        Users::updateUserDetails($userDetails);
        Contact::newOrderEmailForAdministrator($this->getSettingByKey(SettingKeyEnum::getOrderEmailKey()), $user, $finalizedOrder);
        Contact::newOrderEmailForUser($user, $finalizedOrder, $userDetails, $this->getSettingByKey(SettingKeyEnum::getIbanKey()));
        header( 'Location:'.App::config('url').'Trgovina/ZavrsenaKupovina');
    }

    private function getSettingByKey(string $key): ?SettingModel
    {
        return Settings::getByKey($key);
    }

    public function changeStatus(string $orderId): void
    {
        $this->isAdmin();
        $order = OrderValidator::generateFromChangeStatusRequest(Order::get($orderId));
        Order::changeStatus($order);
        header( 'Location:'.App::config('url').'Dashboard/order/'.$order->getId());
    }

    public function changeComment(string $orderId): void
    {
        $this->isAdmin();
        $order = OrderValidator::generateFromChangeCommentRequest(Order::get($orderId));
        Order::changeComment($order);
        header( 'Location:'.App::config('url').'Dashboard/order/'.$order->getId());
    }
}