<?php

declare(strict_types=1);

class ProductCategory
{
    public static function get($productId): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT pc.category_id, pc.id, cnt.name FROM product_category AS pc
                                    LEFT JOIN category_name_translation AS cnt ON pc.category_id=cnt.category_id 
                                    WHERE pc.product_id=:product_id AND cnt.locale=:locale");
        $stmt->bindValue("product_id", $productId);
        $stmt->bindValue("locale", 'hr');
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function create($productId): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO product_category (product_id, category_id) VALUES (:product_id, :category_id)");
        $stmt->bindValue('product_id', $productId);
        $stmt->bindValue('category_id', Request::post('category_id'));
        $stmt->execute();
    }

    public static function delete($productCategory): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM product_category WHERE id=:id");
        $stmt->bindValue("id", $productCategory);
        $stmt->execute();
    }
}