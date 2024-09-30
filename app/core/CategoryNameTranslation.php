<?php

declare(strict_types=1);

use Model\CategoryModel;
use Model\CategoryNameTranslationModel;

class CategoryNameTranslation
{
    public static function getByCategory(CategoryModel $categoryModel): array
    {
        $categoryNameTranslations = [];
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM category_name_translation WHERE category_id=:category_id');
        $stmt->bindValue('category_id', $categoryModel->getId());
        $stmt->execute();
        $results = $stmt->fetchAll();
        if (true === is_bool($results)) {
            return [];
        }

        foreach ($results as $result) {
            $categoryNameTranslations[] = new CategoryNameTranslationModel(
                $result->id,
                $result->category_id,
                $result->locale,
                $result->name
            );
        }
        return $categoryNameTranslations;
    }

    public static function get(string $id): ?CategoryNameTranslationModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM category_name_translation WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }

        return new CategoryNameTranslationModel(
            $result->id,
            $result->category_id,
            $result->locale,
            $result->name
        );
    }

    public static function create(CategoryNameTranslationModel $categoryNameTranslationModel): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO category_name_translation (id, category_id, locale, name) 
        VALUES (:id, :product_id, :locale, :name)');
        $stmt->bindValue('id', $categoryNameTranslationModel->getId());
        $stmt->bindValue('product_id', $categoryNameTranslationModel->getCategoryId());
        $stmt->bindValue('locale', $categoryNameTranslationModel->getLocale());
        $stmt->bindValue('name', $categoryNameTranslationModel->getName());
        $stmt->execute();
    }

    public static function update(CategoryNameTranslationModel $categoryNameTranslationModel): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE category_name_translation SET name=:name WHERE locale=:locale AND category_id=:category_id');
        $stmt->bindValue('name', $categoryNameTranslationModel->getName());
        $stmt->bindValue('locale', $categoryNameTranslationModel->getLocale());
        $stmt->bindValue('category_id', $categoryNameTranslationModel->getCategoryId());
        $stmt->execute();
    }

    public static function delete(CategoryModel $categoryModel): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM category_name_translation WHERE category_id=:category_id");
        $stmt->bindValue('category_id', $categoryModel->getId());
        $stmt->execute();
    }
}