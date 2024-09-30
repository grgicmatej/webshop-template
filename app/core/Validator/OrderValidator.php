<?php

declare(strict_types=1);

namespace Validator;

use Enum\OrdersEnum;
use Model\OrderModel;
use Model\ShoppingCartModel;
use Model\UserModel;

class OrderValidator
{
    public static function generateFromShoppingCart(string $orderId, UserModel $user): ?OrderModel
    {
        return new OrderModel(
            rand(1,100),
            $user,
            $orderId,
            new \DateTimeImmutable(),
            floatval(0),
            floatval(0),
            floatval(0),
            '',
            '',
            '',
        );
    }

    public static function generateNewOrder(ShoppingCartModel $shoppingCart, float $productPrice, float $deliveryPrice, float $totalPrice): ?OrderModel
    {
        return new OrderModel(
            rand(1,100),
            $shoppingCart->getUser(),
            $shoppingCart->getOrder()->getOrderId(),
            new \DateTimeImmutable(),
            $productPrice,
            $deliveryPrice,
            $totalPrice,
            $_POST['payment_method'],
            OrdersEnum::getReceived(),
            '',
        );
    }

    public static function generateFromChangeStatusRequest(OrderModel $order): ?OrderModel
    {
        return new OrderModel(
            $order->getId(),
            $order->getUser(),
            $order->getOrderId(),
            $order->getCreatedAt(),
            $order->getProductPrice(),
            $order->getDeliveryPrice(),
            $order->getTotalPrice(),
            $order->getPaymentMethod(),
            $_POST['status'],
            $order->getComment(),
        );
    }

    public static function generateFromChangeCommentRequest(OrderModel $order): ?OrderModel
    {
        return new OrderModel(
            $order->getId(),
            $order->getUser(),
            $order->getOrderId(),
            $order->getCreatedAt(),
            $order->getProductPrice(),
            $order->getDeliveryPrice(),
            $order->getTotalPrice(),
            $order->getPaymentMethod(),
            $order->getStatus(),
            $_POST['comment']
        );
    }
}