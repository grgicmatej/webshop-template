<?php

declare(strict_types=1);

class ShoppingCart
{
    public static function add($product, $order_id = null): void
    {
        if (null === $order_id) {
            $order_id = Uuid::generateUuid();
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
        $stmt->bindValue('order_id', $order_id);
        $stmt->bindValue('product_id', $product['id']);
        $stmt->bindValue('product_price', $productPrice);
        $stmt->bindValue('product_quantity', 1);
        $stmt->bindValue('user_id', Session::getUserId());
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
                p.*
                FROM shopping_cart AS sc
                LEFT JOIN products AS p ON sc.product_id=p.id
                WHERE sc.user_id=:user_id AND order_completed=:order_completed");
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->bindValue('order_completed', 0);
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
}
