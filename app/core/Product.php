<?php

declare(strict_types=1);

class Product
{
    public static function create($id, $image): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO prodducts 
                    (id, image, price, price_on_sale, active, active_sale_price, created_at, updated_at, sku_number) 
                VALUES 
                    (:id, :image, :price, :price_on_sale, :active, :active_sale_price, NOW(), NOW(), :sku_number)');
        $stmt->bindValue('id', $id);
        $stmt->bindValue('image', $image);
        $stmt->bindValue('price', Request::post('price'));
        $stmt->bindValue('price_on_sale', Request::post('price_on_sale'));
        $stmt->bindValue('active', Request::post('active'));
        $stmt->bindValue('active_sale_price', Request::post('active_sale_price'));
        $stmt->bindValue('sku_number', Request::post('sku_number'));
        $stmt->execute();
    }

    public static function update($id, $image): void
    {
        $db = Db::getInstance();
        if (null === $image) {
            $stmt = $db->prepare('UPDATE products SET price=:price, price_on_sale=:price_on_sale, active=:active, active_sale_price=:active_sale_price,sku_number=:sku_number,updated_at=NOW()');
            $stmt->bindValue('price', Request::post('price'));
            $stmt->bindValue('price_on_sale', Request::post('price_on_sale'));
            $stmt->bindValue('active', Request::post('active'));
            $stmt->bindValue('active_sale_price', Request::post('active_sale_price'));
            $stmt->bindValue('sku_number', Request::post('sku_number'));
            $stmt->execute();
        } else {
            $stmt = $db->prepare('UPDATE products SET price=:price, image=:image, price_on_sale=:price_on_sale, active=:active, active_sale_price=:active_sale_price,sku_number=:sku_number,updated_at=NOW()');
            $stmt->bindValue('price', Request::post('price'));
            $stmt->bindValue('image', Request::post('image'));
            $stmt->bindValue('price_on_sale', Request::post('price_on_sale'));
            $stmt->bindValue('active', Request::post('active'));
            $stmt->bindValue('active_sale_price', Request::post('active_sale_price'));
            $stmt->bindValue('sku_number', Request::post('sku_number'));
            $stmt->execute();
        }
    }

    public static function get($id): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM products WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return (array) $stmt->fetch();
    }

    public static function delete($id)
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('DELETE FROM products WHERE products..id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }
}
