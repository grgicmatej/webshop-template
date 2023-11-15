<?php

declare(strict_types=1);

class Product
{
    public static function all(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT products.id, price, active, pnt.name, pnt.locale FROM products LEFT JOIN product_name_translation AS pnt on products.id = pnt.product_id WHERE pnt.locale=:locale");
        $stmt->bindValue('locale', 'hr');
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }
    public static function create($id, $image): void
    {
        $active = 0;
        if (Request::post('active') == 1) {
            $active = 1;
        }

        $activeOnSale = 0;
        if (Request::post('active_sale_price') == 1) {
            $activeOnSale = 1;
        }
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO products 
                    (id, image, price, price_on_sale, active, active_sale_price, created_at, updated_at, sku_number) 
                VALUES 
                    (:id, :image, :price, :price_on_sale, :active, :active_sale_price, NOW(), NOW(), :sku_number)');
        $stmt->bindValue('id', $id);
        $stmt->bindValue('image', $image);
        $stmt->bindValue('price', Request::post('price'));
        $stmt->bindValue('price_on_sale', Request::post('price_on_sale'));
        $stmt->bindValue('active', $active);
        $stmt->bindValue('active_sale_price', $activeOnSale);
        $stmt->bindValue('sku_number', Request::post('sku_number'));
        $stmt->execute();
    }

    public static function update($id, $image): void
    {
        $active = 0;
        if (Request::post('active') == 1) {
            $active = 1;
        }

        $activeOnSale = 0;
        if (Request::post('active_sale_price') == 1) {
            $activeOnSale = 1;
        }
        $db = Db::getInstance();
        if (null === $image) {
            $stmt = $db->prepare('UPDATE products SET price=:price, price_on_sale=:price_on_sale, active=:active, active_sale_price=:active_sale_price,sku_number=:sku_number,updated_at=NOW() WHERE id=:id');
            $stmt->bindValue('price', Request::post('price'));
            $stmt->bindValue('price_on_sale', Request::post('price_on_sale'));
            $stmt->bindValue('active', $active);
            $stmt->bindValue('active_sale_price', $activeOnSale);
            $stmt->bindValue('sku_number', Request::post('sku_number'));
            $stmt->bindValue('id', $id);
        } else {
            $stmt = $db->prepare('UPDATE products SET price=:price, image=:image, price_on_sale=:price_on_sale, active=:active, active_sale_price=:active_sale_price,sku_number=:sku_number,updated_at=NOW() WHERE id=:id');
            $stmt->bindValue('price', Request::post('price'));
            $stmt->bindValue('image', $image);
            $stmt->bindValue('price_on_sale', Request::post('price_on_sale'));
            $stmt->bindValue('active', $active);
            $stmt->bindValue('active_sale_price', $activeOnSale);
            $stmt->bindValue('sku_number', Request::post('sku_number'));
            $stmt->bindValue('id', $id);
        }
        $stmt->execute();
    }

    public static function get($id): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM products WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function delete($id): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('DELETE FROM products WHERE products.id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }
}
