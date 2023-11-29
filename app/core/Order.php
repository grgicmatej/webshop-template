<?php

declare(strict_types=1);

class Order
{
    public const ORDER_RECEIVED = 'Zaprimljeno';
    //@todo add other statuses into some kind of enum
    public static function all(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM orders");
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function getTotalAmounts()
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT SUM(total_price) AS total FROM orders");
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function newOrder(string $orderId, float $productsTotal, float $deliveryTotal, float $total): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO orders (id, user_id, order_id, created_at, product_price, delivery_price, total_price, payment_method, status, comment) 
                    VALUES (:id, :user_id, :order_id, now(), :product_price, :delivery_price, :total_price, :payment_method, :status, :comment)");
        $stmt->bindValue('id', Uuid::generateUuid());
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->bindValue('order_id', $orderId);
        $stmt->bindValue('product_price', $productsTotal);
        $stmt->bindValue('delivery_price', $deliveryTotal);
        $stmt->bindValue('total_price', $total);
        $stmt->bindValue('payment_method', $_POST['payment_method']);
        $stmt->bindValue('status', self::ORDER_RECEIVED);
        $stmt->bindValue('comment', '');
        $stmt->execute();
    }
}
