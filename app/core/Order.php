<?php

declare(strict_types=1);

use Enum\OrdersEnum;
use Model\OrderModel;

class Order
{
    /**
     * @throws Exception
     */
    public static function all(): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $result) {
            $results[] = new OrderModel(
                intval($result->id),
                Users::get($result->user_id),
                $result->order_id,
                new DateTimeImmutable($result->created_at),
                floatval($result->product_price),
                floatval($result->delivery_price),
                floatval($result->total_price),
                $result->payment_method,
                $result->status,
                $result->comment,
            );
        }

        return $results;
    }

    public static function get(string $id): ?OrderModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM orders WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new OrderModel(
            intval($result->id),
            Users::get($result->user_id),
            $result->order_id,
            new DateTimeImmutable($result->created_at),
            floatval($result->product_price),
            floatval($result->delivery_price),
            floatval($result->total_price),
            $result->payment_method,
            $result->status,
            $result->comment,
        );
    }

    public static function getByOrderId(OrderModel $order): ?OrderModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM orders WHERE order_id=:order_id');
        $stmt->bindValue('order_id', $order->getOrderId());
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new OrderModel(
            intval($result->id),
            Users::get($result->user_id),
            $order->getOrderId(),
            new DateTimeImmutable($result->created_at),
            floatval($result->product_price),
            floatval($result->delivery_price),
            floatval($result->total_price),
            $result->payment_method,
            $result->status,
            $result->comment,
        );
    }

    public static function getAllNewOrders(): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM orders WHERE status=:status ORDER BY created_at DESC");
        $stmt->bindValue('status', OrdersEnum::getReceived());
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $result) {
            $results[] = new OrderModel(
                intval($result->id),
                Users::get($result->user_id),
                $result->order_id,
                new DateTimeImmutable($result->created_at),
                floatval($result->product_price),
                floatval($result->delivery_price),
                floatval($result->total_price),
                $result->payment_method,
                $result->status,
                $result->comment,
            );
        }

        return $results;
    }

    public static function getTotalAmounts(): string
    {
        $total = number_format(floatval(0), 2);
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT SUM(total_price) AS total FROM orders");
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return $total;
        }

        if (null === $result->total) {
            return $total;
        }

        return number_format(floatval(intval($result->total)), 2);
    }

    public static function newOrder(OrderModel $order): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO orders (user_id, order_id, created_at, product_price, delivery_price, total_price, payment_method, status, comment) 
                    VALUES (:user_id, :order_id, now(), :product_price, :delivery_price, :total_price, :payment_method, :status, :comment)");
        $stmt->bindValue('user_id', $order->getUser()->getId());
        $stmt->bindValue('order_id', $order->getOrderId());
        $stmt->bindValue('product_price', $order->getProductPrice());
        $stmt->bindValue('delivery_price', $order->getDeliveryPrice());
        $stmt->bindValue('total_price', $order->getTotalPrice());
        $stmt->bindValue('payment_method', $order->getPaymentMethod());
        $stmt->bindValue('status', $order->getStatus());
        $stmt->bindValue('comment', $order->getComment());
        $stmt->execute();
    }

    public static function changeStatus(OrderModel $order): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE orders SET status=:status WHERE id=:id");
        $stmt->bindValue('id', $order->getId());
        $stmt->bindValue('status', $order->getStatus());
        $stmt->execute();
    }

    public static function changeComment(OrderModel $order): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE orders SET comment=:comment WHERE id=:id");
        $stmt->bindValue('id', $order->getId());
        $stmt->bindValue('comment', $order->getComment());
        $stmt->execute();
    }
}
