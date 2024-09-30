<?php

declare(strict_types=1);

use Dto\ProductCategoryDto;
use Model\CategoryModel;
use Model\ProductCategoryModel;
use Model\ProductModel;

class ProductCategory
{
    public static function get(ProductModel $product): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT pc.category_id, pc.id, cnt.id as cid FROM product_category AS pc
                                    LEFT JOIN category_name_translation AS cnt ON pc.category_id=cnt.category_id 
                                    WHERE pc.product_id=:product_id AND cnt.locale=:locale");
        $stmt->bindValue("product_id", $product->getId());
        $stmt->bindValue("locale", 'hr');
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ProductCategoryDto(
                intval($qResult->id),
                $product,
                Category::get($qResult->category_id),
                CategoryNameTranslation::get($qResult->cid)
            );
        }

        return $results;
    }

    public static function getById(string $productCategoryId): ?ProductCategoryModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_category WHERE id=:id');
        $stmt->bindValue('id', $productCategoryId);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ProductCategoryModel(
            strval($result->id),
            Product::get($result->product_id),
            Category::get($result->category_id)
        );
    }

    public static function getProductsByCategory(CategoryModel $category): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT pc.*,p.*,pnt.*, pi.image as pimage
                                    FROM product_category AS pc 
                                    LEFT JOIN products AS p ON pc.product_id=p.id
                                    LEFT JOIN product_name_translation AS pnt ON p.id=pnt.product_id
                                    LEFT JOIN product_image AS pi ON p.id=pi.product_id
                                    WHERE pnt.locale=:locale AND pc.category_id=:category
                                    AND pi.primary_image=true
                                    AND p.soft_deleted=false
                                    AND p.active=true
                                    ORDER BY p.created_at DESC
                               ");
        $stmt->bindValue("locale", 'hr');
        $stmt->bindValue("category", $category->getId());
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function create(ProductCategoryModel $productCategory): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO product_category (product_id, category_id) VALUES (:product_id, :category_id)");
        $stmt->bindValue('product_id', $productCategory->getProduct()->getId());
        $stmt->bindValue('category_id', $productCategory->getCategory()->getId());
        $stmt->execute();
    }

    public static function delete(ProductCategoryModel $productCategory): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM product_category WHERE id=:id");
        $stmt->bindValue("id", $productCategory->getId());
        $stmt->execute();
    }
}