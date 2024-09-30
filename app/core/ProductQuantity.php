<?php

declare(strict_types=1);

use Model\ColorModel;
use Model\ProductModel;
use Model\ProductQuantityModel;
use Model\ShoppingCartModel;
use Model\SizeModel;

class ProductQuantity
{
    public static function create(ProductQuantityModel $productQuantity): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO product_quantity (id, product_id, color_id, size_id, available, reserved, sold) VALUES 
                                (:id, :product_id, :color_id, :size_id, :available, :reserved, :sold)');
        $stmt->bindValue('id', $productQuantity->getId());
        $stmt->bindValue('product_id', $productQuantity->getProduct()->getId());
        $stmt->bindValue('color_id', $productQuantity->getColor()->getId());
        $stmt->bindValue('size_id', $productQuantity->getSize()->getId());
        $stmt->bindValue('available', $productQuantity->getAvailable());
        $stmt->bindValue('reserved', $productQuantity->getReserved());
        $stmt->bindValue('sold', $productQuantity->getSold());
        $stmt->execute();
    }

    public static function get(ProductModel $product): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT pq.id as pid,
                                            pq.*,
                                            c.*,
                                            s.*
                                            FROM product_quantity AS pq
                                            LEFT JOIN color AS c on pq.color_id=c.id
                                            LEFT JOIN size AS s on pq.size_id=s.id
                                    WHERE pq.product_id=:id');
        $stmt->bindValue('id', $product->getId());
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ProductQuantityModel(
                $qResult->pid,
                $product,
                Color::get($qResult->color_id),
                Size::get($qResult->size_id),
                intval($qResult->available),
                intval($qResult->reserved),
                intval($qResult->sold)
            );
        }

        return $results;
    }

    public static function getById(string $id): ?ProductQuantityModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_quantity WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ProductQuantityModel(
            $id,
            Product::get($result->product_id),
            Color::get($result->color_id),
            Size::get($result->size_id),
            intval($result->available),
            intval($result->reserved),
            intval($result->sold));
    }

    public static function checkIfExist(ProductQuantityModel $productQuantity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_quantity WHERE product_id=:product_id AND color_id=:color_id AND size_id=:size_id');
        $stmt->bindValue('product_id', $productQuantity->getProduct()->getId());
        $stmt->bindValue('color_id', $productQuantity->getColor()->getId());
        $stmt->bindValue('size_id', $productQuantity->getSize()->getId());
        $stmt->execute();
        $result = $stmt->fetch();

        return !(false === $result);
    }

    public static function update(string $productId): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_quantity SET available=:available WHERE product_id=:product_id');
        $stmt->bindValue('available', Request::post('available'));
        $stmt->bindValue('product_id', $productId);
        $stmt->execute();
    }

    public static function deleteSoldNumber(ProductQuantityModel $productQuantity): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_quantity SET sold=0 WHERE id=:id');
        $stmt->bindValue('id', $productQuantity->getId());
        $stmt->execute();
    }

    public static function editAvailableNumber(ProductQuantityModel $productQuantity): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_quantity SET available=:available WHERE id=:id');
        $stmt->bindValue('id', $productQuantity->getId());
        $stmt->bindValue('available', $productQuantity->getAvailable());
        $stmt->execute();
    }

    public static function deleteQuantity(ProductQuantityModel $productQuantity): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('DELETE FROM product_quantity WHERE id=:id');
        $stmt->bindValue('id', $productQuantity->getId());
        $stmt->execute();
    }

    public static function getByValues(ProductModel $product, SizeModel $size, ColorModel $color): ?ProductQuantityModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM product_quantity WHERE product_id=:product_id AND color_id=:color_id AND size_id=:size_id');
        $stmt->bindValue('product_id', $product->getId());
        $stmt->bindValue('color_id', $color->getId());
        $stmt->bindValue('size_id', $size->getId());
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ProductQuantityModel(
            $result->id,
            $product,
            $color,
            $size,
            intval($result->available),
            intval($result->reserved),
            intval($result->sold)
        );
    }

    public static function updateReservedNumber(ProductQuantityModel $productQuantity): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_quantity SET reserved=reserved+1 WHERE id=:id');
        $stmt->bindValue('id', $productQuantity->getId());
        $stmt->execute();
    }

    public static function updateReservedNumberAfterExpiredShoppingCart(array $shoppingCarts): void
    {
        /** @var ShoppingCartModel $shoppingCart */
        foreach ($shoppingCarts as $shoppingCart) {
            $db = Db::getInstance();
            $stmt = $db->prepare('UPDATE product_quantity SET reserved=reserved-1 WHERE color_id =:color_id AND size_id=:size_id AND product_id=:product_id');
            $stmt->bindValue('color_id', $shoppingCart->getColor()->getId());
            $stmt->bindValue('size_id', $shoppingCart->getSize()->getId());
            $stmt->bindValue('product_id', $shoppingCart->getProduct()->getId());
            $stmt->execute();
        }
    }

    public static function updateReservedNumberAfterDeletedShoppingCart(ShoppingCartModel $shoppingCart): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('UPDATE product_quantity SET reserved=reserved-1 WHERE color_id =:color_id AND size_id=:size_id AND product_id=:product_id');
        $stmt->bindValue('color_id', $shoppingCart->getColor()->getId());
        $stmt->bindValue('size_id', $shoppingCart->getSize()->getId());
        $stmt->bindValue('product_id', $shoppingCart->getProduct()->getId());
        $stmt->execute();
    }
}
