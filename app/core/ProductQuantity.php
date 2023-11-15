<?php

declare(strict_types=1);

class ProductQuantity
{
    public static function create($productId): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO product_quantity (id, product_id, available, reserved, sold) VALUES 
                                (:id, :product_id, :available, :reserved, :sold)');
        $stmt->bindValue('id', Uuid::generateUuid());
        $stmt->bindValue('product_id', $productId);
        $stmt->bindValue('available', Request::post('available'));
        $stmt->bindValue('reserved', 0);
        $stmt->bindValue('sold', 0);
        $stmt->execute();
    }

    public static function get($productId): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_quantity WHERE product_id=:id');
        $stmt->bindValue('id', $productId);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function update($productId): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_quantity SET available=:available WHERE product_id=:product_id');
        $stmt->bindValue('available', Request::post('available'));
        $stmt->bindValue('product_id', $productId);
        $stmt->execute();
    }
}