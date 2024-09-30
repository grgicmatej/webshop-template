<?php

declare(strict_types=1);

use Model\ProductModel;
use Model\ProductNameTranslationModel;

class ProductNameTranslation
{
    public static function create(ProductNameTranslationModel $productNameTranslationModel): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO product_name_translation (id, product_id, locale, name) 
        VALUES (:id, :product_id, :locale, :name)');
        $stmt->bindValue('id', $productNameTranslationModel->getId());
        $stmt->bindValue('product_id', $productNameTranslationModel->getProduct()->getId());
        $stmt->bindValue('locale', $productNameTranslationModel->getLocale());
        $stmt->bindValue('name', $productNameTranslationModel->getName());
        $stmt->execute();
    }

    public static function all(ProductModel $product): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_name_translation WHERE product_id=:product_id');
        $stmt->bindValue('product_id', $product->getId());
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ProductNameTranslationModel(
                $qResult->id,
                $product,
                $qResult->locale,
                $qResult->name
            );
        }

        return $results;
    }

    public static function update(ProductNameTranslationModel $productNameTranslationModel): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_name_translation SET name=:name WHERE locale=:locale AND product_id=:product_id');
        $stmt->bindValue('name', $productNameTranslationModel->getName());
        $stmt->bindValue('locale', $productNameTranslationModel->getLocale());
        $stmt->bindValue('product_id', $productNameTranslationModel->getProduct()->getId());
        $stmt->execute();
    }

    public static function get(string $id)
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_name_translation WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }

        return new ProductNameTranslationModel(
            $result->id,
            Product::get($result->product_id),
            $result->locale,
            $result->name
        );
    }

    public static function getByProductModel(ProductModel $product): ?ProductNameTranslationModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_name_translation WHERE product_id=:product_id AND locale=:locale');
        $stmt->bindValue('product_id', $product->getId());
        $stmt->bindValue('locale', 'hr');
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }

        return new ProductNameTranslationModel(
            $result->id,
            Product::get($result->product_id),
            $result->locale,
            $result->name
        );
    }
}