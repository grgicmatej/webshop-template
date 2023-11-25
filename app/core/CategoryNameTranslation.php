<?php

declare(strict_types=1);

class CategoryNameTranslation
{
    public static function get($categoryId): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM category_name_translation WHERE category_id=:category_id');
        $stmt->bindValue('category_id', $categoryId);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function create($categoryId, $locale): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO category_name_translation (id, category_id, locale, name) 
        VALUES (:id, :product_id, :locale, :name)');
        $stmt->bindValue('id', Uuid::generateUuid());
        $stmt->bindValue('product_id', $categoryId);
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

    public static function update($categoryId, $locale): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE category_name_translation SET name=:name WHERE locale=:locale AND category_id=:category_id');
        switch ($locale) {
            case 'en':
                $stmt->bindValue('name', Request::post('name_en'));
                break;
            default:
                $stmt->bindValue('name', Request::post('name_hr'));
                break;
        }        $stmt->bindValue('locale', $locale);
        $stmt->bindValue('category_id', $categoryId);
        $stmt->execute();
    }
}