<?php

declare(strict_types=1);

class Order
{
    public const ORDER_RECEIVED = 'Zaprimljeno';
    //@todo add other statuses into some kind of enum
    public static function all(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function allNew(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM orders WHERE status=:status ORDER BY created_at DESC");
        $stmt->bindValue('status', 'Zaprimljeno');
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
        $stmt = $db->prepare("INSERT INTO orders (id, user_id, created_at, product_price, delivery_price, total_price, payment_method, status, comment) 
                    VALUES (:id, :user_id, now(), :product_price, :delivery_price, :total_price, :payment_method, :status, :comment)");
        $stmt->bindValue('id', $orderId);
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

    public static function get(string $id): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT o.*, o.id AS oid, u.*, ud.* FROM orders AS o
                    LEFT JOIN users AS u ON o.user_id = u.id
                    LEFT JOIN users_details AS ud ON o.user_id = ud.user_id 
                    WHERE o.id =:id
                    ");
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return (array) $stmt->fetchAll();
    }

    public static function changeStatus($id): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE orders SET status=:status WHERE id=:id");
        $stmt->bindValue('status', $_POST['status']);
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    public static function changeComment($id): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE orders SET comment=:comment WHERE id=:id");
        $stmt->bindValue('comment', $_POST['comment']);
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }
}
