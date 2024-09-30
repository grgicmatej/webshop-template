<?php

declare(strict_types=1);

use Model\ProductImageModel;
use Model\ProductModel;

class ProductImage
{
    public static function create(ProductImageModel $productImage): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO product_image (id, product_id, image) VALUES (:id, :product_id, :image)');
        $stmt->bindValue('id', $productImage->getId());
        $stmt->bindValue('product_id', $productImage->getProduct()->getId());
        $stmt->bindValue('image', $productImage->getImage());
        return $stmt->execute();
    }

    public static function getByProductId(ProductModel $product): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_image WHERE product_id=:product_id ORDER BY primary_image DESC');
        $stmt->bindValue('product_id', $product->getId());
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ProductImageModel(
                $qResult->id,
                $product,
                $qResult->image,
                boolval($qResult->primary_image)
            );
        }

        return $results;
    }

    public static function getByImageId(string $id): ?ProductImageModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_image WHERE id=:id ORDER BY primary_image DESC');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }

        return new ProductImageModel(
            $result->id,
            Product::get($result->product_id),
            $result->image,
            boolval($result->primary_image)
        );
    }

    public static function deleteImage(ProductImageModel $productImage): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('DELETE FROM product_image WHERE id=:id');
        $stmt->bindValue('id', $productImage->getId());
        $stmt->execute();
    }

    public static function removePrimaryImage(ProductModel $product): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_image SET primary_image=0 WHERE product_id=:product_id');
        $stmt->bindValue('product_id', $product->getId());
        $stmt->execute();
    }

    public static function setPrimaryImage(ProductImageModel $productImage): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_image SET primary_image=1 WHERE id=:id');
        $stmt->bindValue('id', $productImage->getId());
        $stmt->execute();
    }

    public static function getPrimaryImageByProduct(ProductModel $product): ?ProductImageModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_image WHERE product_id=:product_id AND primary_image=true');
        $stmt->bindValue('product_id', $product->getId());
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }

        return new ProductImageModel(
            $result->id,
            $product,
            $result->image,
            boolval($result->primary_image)
        );
    }
}