<?php

declare(strict_types=1);

class ShoppingCart
{
    public static function add($product, $orderId = null): void
    {
        if (null === $orderId) {
            $orderId = Uuid::generateUuid();
        } else {
            self::updateValidUntil($orderId);
        }
        if (1 === $product['active_sale_price']) {
            $productPrice = $product['price_on_sale'];
        } else {
            $productPrice = $product['price'];
        }

        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO shopping_cart 
                    (id, order_id, product_id, product_price, product_quantity, valid_until, user_id, created_at) 
            VALUES (:id, :order_id, :product_id, :product_price, :product_quantity, DATE_ADD(NOW(), INTERVAL 1 HOUR ), :user_id, NOW())");
        $stmt->bindValue('id', Uuid::generateUuid());
        $stmt->bindValue('order_id', $orderId);
        $stmt->bindValue('product_id', $product['id']);
        $stmt->bindValue('product_price', $productPrice);
        $stmt->bindValue('product_quantity', 1);
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->execute();
    }

    public static function getOrderId(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT order_id FROM shopping_cart WHERE user_id=:user_id AND order_completed=:order_completed LIMIT 1");
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->bindValue('order_completed', 0);
        $stmt->execute();
        return (array) $stmt->fetch();
    }

    public static function updateOrderStatus(string $orderId)
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE shopping_cart SET order_completed=:order_completed WHERE order_id=:order_id");
        $stmt->bindValue('order_completed', 1);
        $stmt->bindValue('order_id', $orderId);
        $stmt->execute();
    }

    public static function sum()
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT SUM(product_price) AS total FROM shopping_cart WHERE user_id=:user_id AND order_completed=0");
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function updateValidUntil(string $orderId): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE shopping_cart SET valid_until=DATE_ADD(NOW(), INTERVAL 1 HOUR ) WHERE order_id=:order_id");
        $stmt->bindValue('order_id', $orderId);
        $stmt->execute();
    }

    public static function checkIfExist(): bool|array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT order_id, product_id FROM shopping_cart WHERE user_id=:user_id AND order_completed=:order_completed LIMIT 1");
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->bindValue('order_completed', false);
        $stmt->execute();
        $result = $stmt->fetch();

        return false === $result ? false : (array) $result;
    }

    public static function get(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT 
                sc.*,
                sc.id as scid,
                p.*,
                pnt.*
                FROM shopping_cart AS sc
                LEFT JOIN products AS p ON sc.product_id=p.id
                LEFT JOIN product_name_translation AS pnt ON sc.product_id=pnt.product_id
                WHERE sc.user_id=:user_id AND order_completed=:order_completed AND pnt.locale=:locale");
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->bindValue('order_completed', 0);
        $stmt->bindValue('locale', 'hr');
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (false === $result) {
            return [];
        }

        return $result;
    }

    public static function deleteExpiredEntry(): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM shopping_cart WHERE valid_until<NOW() AND order_completed=false");
        $stmt->execute();
    }

    public static function remove($id): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM shopping_cart WHERE id=:id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    public static function getProductsByOrderId(string $order_id): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT sc.*, p.*, p.id as pid, pnt.* FROM shopping_cart AS sc
                            LEFT JOIN products AS p ON sc.product_id=p.id
                            LEFT JOIN product_name_translation AS pnt ON sc.product_id=pnt.product_id
                            WHERE sc.order_id=:id AND pnt.locale=:locale
");
        $stmt->bindValue('id', $order_id);
        $stmt->bindValue('locale', 'hr');
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
