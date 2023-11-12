<?php

declare(strict_types=1);

class ProductNameTranslation
{
    public static function create($productId, $locale): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO product_name_translation (id, product_id, locale, name) 
        VALUES (:id, :product_id, :locale, :name)');
        $stmt->bindValue('id', Uuid::generateUuid());
        $stmt->bindValue('product_id', $productId);
        $stmt->bindValue('locale', $locale);
        switch ($locale) {
            case 'en':
                $stmt->bindValue('name', Request::post('name_en'));
                break;
            default:
                $stmt->bindValue('name', Request::post('name_hr'));
                break;
        }
        $stmt->execute();
    }

    public static function get($productId): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_name_translation WHERE product_id=:product_id');
        $stmt->bindValue('product_id', $productId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function update($productId, $locale): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_name_translation SET name=:name WHERE locale=:locale AND product_id=:product_id');
        switch ($locale) {
            case 'en':
                $stmt->bindValue('name', Request::post('name_en'));
                break;
            default:
                $stmt->bindValue('name', Request::post('name_hr'));
                break;
        }        $stmt->bindValue('locale', $locale);
        $stmt->bindValue('product_id', $productId);
        $stmt->execute();
    }
}