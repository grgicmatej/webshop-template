<?php

declare(strict_types=1);

use Dto\DashboardProductDto;
use Dto\ProductDto;
use Dto\StoreProductDto;
use Model\ProductModel;

class Product
{
    public static function all(): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT products.id, price, active, pnt.name, pnt.locale FROM products LEFT JOIN product_name_translation AS pnt on products.id = pnt.product_id WHERE pnt.locale=:locale AND products.soft_deleted=false");
        $stmt->bindValue('locale', 'hr');
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new DashboardProductDto(
                $qResult->id,
                floatval($qResult->price),
                intval($qResult->active),
                $qResult->name,
                $qResult->name
            );
        }

        return $results;
    }

    public static function allActive(): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT products.id, price, active, pnt.name, pnt.locale FROM products LEFT JOIN product_name_translation AS pnt on products.id = pnt.product_id WHERE pnt.locale=:locale AND products.soft_deleted=false AND products.active=true");
        $stmt->bindValue('locale', 'hr');
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new DashboardProductDto(
                $qResult->id,
                floatval($qResult->price),
                intval($qResult->active),
                $qResult->name,
                $qResult->name
            );
        }

        return $results;
    }

    public static function allProductData(string $id): ?StoreProductDto
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT p.*, pnt.*, pt.* FROM products AS p 
                        LEFT JOIN product_name_translation AS pnt on p.id = pnt.product_id 
                        LEFT JOIN product_translations AS pt ON p.id=pt.product_id
                        WHERE pnt.locale=:locale AND p.soft_deleted=false AND p.id=:id AND pt.locale=:locale");
        $stmt->bindValue('locale', 'hr');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $queryResults = $stmt->fetch();
        if (true === is_bool($queryResults)) {
            return null;
        }

        return new StoreProductDto(
            $queryResults->product_id,
            floatval($queryResults->price),
            floatval($queryResults->price_on_sale),
            intval($queryResults->active),
            intval($queryResults->active_sale_price),
            intval($queryResults->personalized),
            new DateTimeImmutable($queryResults->created_at),
            new DateTimeImmutable($queryResults->updated_at),
            intval($queryResults->sku_number),
            intval($queryResults->soft_deleted),
            intval($queryResults->featured),
            intval($queryResults->popularity),
            $queryResults->locale,
            $queryResults->name,
            $queryResults->description
            );

    }

    public static function create(ProductModel $product): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO products 
                    (id, price, price_on_sale, active, active_sale_price, featured, created_at, updated_at, sku_number) 
                VALUES 
                    (:id, :price, :price_on_sale, :active, :active_sale_price, :featured, NOW(), NOW(), :sku_number)');
        $stmt->bindValue('id', $product->getId());
        $stmt->bindValue('price', $product->getPrice());
        $stmt->bindValue('price_on_sale', $product->getPriceOnSale());
        $stmt->bindValue('active', $product->isActive());
        $stmt->bindValue('active_sale_price', $product->isActiveSalePrice());
        $stmt->bindValue('featured', $product->isFeatured());
        $stmt->bindValue('sku_number', $product->getSkuNumber());
        return $stmt->execute();
    }

    public static function update(ProductModel $product): void
    {
        $db = Db::getInstance();

        $stmt = $db->prepare('UPDATE products SET price=:price, price_on_sale=:price_on_sale, active=:active, featured=:featured, active_sale_price=:active_sale_price,sku_number=:sku_number,updated_at=NOW() WHERE id=:id');
        $stmt->bindValue('price', $product->getPrice());
        $stmt->bindValue('id', $product->getId());
        $stmt->bindValue('price_on_sale', $product->getPriceOnSale());
        $stmt->bindValue('active', $product->isActive());
        $stmt->bindValue('active_sale_price', $product->isActiveSalePrice());
        $stmt->bindValue('featured', $product->isFeatured());
        $stmt->bindValue('sku_number', $product->getSkuNumber());
        $stmt->execute();
    }

    /**
     * @throws Exception
     */
    public static function get(string $id): ?ProductModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM products WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ProductModel(
            $result->id,
            floatval($result->price),
            floatval($result->price_on_sale),
            intval($result->active),
            intval($result->active_sale_price),
            intval($result->personalized),
            New DateTimeImmutable($result->created_at),
            New DateTimeImmutable($result->updated_at),
            intval($result->sku_number),
            intval($result->soft_deleted),
            intval($result->featured),
            intval($result->popularity)
        );
    }

    public static function delete(ProductModel $product): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE products SET soft_deleted=true WHERE id=:id');
        $stmt->bindValue('id', $product->getId());
        $stmt->execute();
    }

    public static function getProductsByFeaturedStatus(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT p.*,pnt.*, pi.image as pimage
                                    FROM products AS p 
                                    LEFT JOIN product_name_translation AS pnt ON p.id=pnt.product_id
                                    LEFT JOIN product_image AS pi ON p.id=pi.product_id
                                    WHERE pnt.locale=:locale
                                    AND p.featured=true
                                    AND p.active=true
                                    AND pi.primary_image=true
                                    AND p.soft_deleted=false
                                    ORDER BY RAND() LIMIT 6
                               ");
        $stmt->bindValue("locale", 'hr');
        $stmt->execute();
        $qResult = $stmt->fetchAll();

        if (true === is_bool($qResult)) {
            return [];
        }

        if (3 > count($qResult)) {
            return [];
        } elseif (6 > count($qResult)) {
            return array_slice(self::getProductDtos($qResult), 0, 3);
        } else {
            return array_slice(self::getProductDtos($qResult), 0, 6);
        }
    }

    public static function getProductsByPopularity(bool $shuffle, int $limit): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT p.*,pnt.*, pi.image as pimage
                                    FROM products AS p 
                                    LEFT JOIN product_name_translation AS pnt ON p.id=pnt.product_id
                                    LEFT JOIN product_image AS pi ON p.id=pi.product_id
                                    WHERE pnt.locale=:locale
                                    AND pi.primary_image=true
                                    AND p.soft_deleted=false
                                    and p.active=true
                                    ORDER BY p.popularity DESC LIMIT 6
                               ");
        $stmt->bindValue("locale", 'hr');
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (true === is_bool($result)) {
            return [];
        }

        if ($limit === count($result)) {
            return [];
        } else {
            $qResult = self::getProductDtos($result);
            if (true === $shuffle) {
                shuffle($qResult);
            }
            return $qResult;
        }
    }

    public static function updatePopularity(ProductModel $product): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE products SET popularity=(popularity+1) WHERE id=:id');
        $stmt->bindValue('id', $product->getId());
        $stmt->execute();
    }

    private static function getProductDtos(array $products): array
    {
        $qr = [];

        foreach ($products as $r) {
            $qr[] = new ProductDto(
                $r->id,
                floatval($r->price),
                floatval($r->price_on_sale),
                intval($r->active),
                intval($r->active_sale_price),
                intval($r->personalized),
                New DateTimeImmutable($r->created_at),
                New DateTimeImmutable($r->updated_at),
                intval($r->sku_number),
                intval($r->soft_deleted),
                intval($r->featured),
                intval($r->popularity),
                $r->product_id,
                $r->locale,
                $r->name,
                $r->pimage
            );
        }

        return $qr;
    }
}
