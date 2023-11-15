<?php

declare(strict_types=1);

class ProductTranslation
{
    public static function create($productId, $locale)
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO product_translations (id, product_id, locale, description) 
        VALUES (:id, :product_id, :locale, :description)');
        $stmt->bindValue('id', Uuid::generateUuid());
        $stmt->bindValue('product_id', $productId);
        $stmt->bindValue('locale', $locale);
        switch ($locale) {
            case 'en':
                $stmt->bindValue('description', Request::post('description_en'));
                break;
            default:
                $stmt->bindValue('description', Request::post('description_hr'));
                break;
        }
        $stmt->execute();
    }

    public static function get($productId): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_translations WHERE product_id=:product_id');
        $stmt->bindValue('product_id', $productId);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function update($productId, $locale): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_translations SET description=:description WHERE locale=:locale AND product_id=:product_id');
        switch ($locale) {
            case 'en':
                $stmt->bindValue('description', Request::post('description_en'));
                break;
            default:
                $stmt->bindValue('description', Request::post('description_hr'));
                break;
        }
        $stmt->bindValue('locale', $locale);
        $stmt->bindValue('product_id', $productId);
        $stmt->execute();
    }
}