<?php

declare(strict_types=1);

use Model\ProductModel;
use Model\ProductTranslationModel;

class ProductTranslation
{
    public static function create(ProductTranslationModel $productTranslation): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO product_translations (id, product_id, locale, description) 
        VALUES (:id, :product_id, :locale, :description)');
        $stmt->bindValue('id', $productTranslation->getId());
        $stmt->bindValue('product_id', $productTranslation->getProduct()->getId());
        $stmt->bindValue('locale', $productTranslation->getLocale());
        $stmt->bindValue('description', $productTranslation->getDescription());
        $stmt->execute();
    }

    public static function get(ProductModel $product): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_translations WHERE product_id=:product_id');
        $stmt->bindValue('product_id', $product->getId());
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ProductTranslationModel(
                $qResult->id,
                $product,
                $qResult->locale,
                $qResult->description
            );
        }

        return $results;
    }

    public static function update(ProductTranslationModel $productTranslation): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_translations SET description=:description WHERE locale=:locale AND product_id=:product_id');
        $stmt->bindValue('description', $productTranslation->getDescription());
        $stmt->bindValue('locale', $productTranslation->getLocale());
        $stmt->bindValue('product_id', $productTranslation->getProduct()->getId());
        $stmt->execute();
    }
}