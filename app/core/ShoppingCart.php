<?php

declare(strict_types=1);

use Dto\ShoppingCartDto;
use Dto\ShoppingCartSumDto;
use Model\OrderModel;
use Model\ProductModel;
use Model\ProductQuantityModel;
use Model\ShoppingCartModel;
use Model\UserModel;
use Validator\OrderValidator;

class ShoppingCart
{
    public static function add(ProductModel $product, ProductQuantityModel $productQuantity, ?ShoppingCartModel $shoppingCart = null): void
    {
        if (null === $shoppingCart) {
            $orderId = Uuid::generateUuid();
        } else {
            $orderId = $shoppingCart->getOrder()->getOrderId();
            self::updateValidUntil($orderId);
        }

        $productPrice = $product->isActiveSalePrice() ? $product->getPriceOnSale() : $product->getPrice();

        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO shopping_cart 
                    (id, order_id, product_id, product_price, color, size, valid_until, user_id, created_at) 
            VALUES (:id, :order_id, :product_id, :product_price, :color, :size, DATE_ADD(NOW(), INTERVAL 1 HOUR ), :user_id, NOW())");
        $stmt->bindValue('id', Uuid::generateUuid());
        $stmt->bindValue('order_id', $orderId);
        $stmt->bindValue('product_id', $product->getId());
        $stmt->bindValue('product_price', $productPrice);
        $stmt->bindValue('color', $productQuantity->getColor()->getName());
        $stmt->bindValue('size', $productQuantity->getSize()->getName());
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->execute();
    }

    /**
     * @throws Exception
     */
    public static function getShoppingCart(UserModel $user): ?ShoppingCartModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM shopping_cart WHERE user_id=:user_id AND order_completed=false LIMIT 1");
        $stmt->bindValue('user_id', $user->getId());
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ShoppingCartModel(
            $result->id,
            OrderValidator::generateFromShoppingCart($result->order_id, Users::get($result->user_id)),
            Product::get($result->product_id),
            floatval($result->product_price),
            Color::getByValue($result->color),
            Size::getByValue($result->size),
            new DateTimeImmutable($result->valid_until),
            Users::get($result->user_id),
            new DateTimeImmutable($result->created_at),
            boolval($result->order_completed),
        );
    }

    public static function updateOrderStatus(OrderModel $order): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE shopping_cart SET order_completed=true WHERE order_id=:order_id");
        $stmt->bindValue('order_id', $order->getOrderId());
        $stmt->execute();
    }

    public static function sum(): ShoppingCartSumDto
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT SUM(product_price) AS total FROM shopping_cart WHERE user_id=:user_id AND order_completed=0");
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->execute();
        $result = $stmt->fetch();
        if (null === $result->total) {
            return new ShoppingCartSumDto('0.00');
        }

        return new ShoppingCartSumDto($result->total);
    }

    public static function updateValidUntil(string $orderId): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE shopping_cart SET valid_until=DATE_ADD(NOW(), INTERVAL 1 HOUR ) WHERE order_id=:order_id");
        $stmt->bindValue('order_id', $orderId);
        $stmt->execute();
    }

    /**
     * @throws Exception
     */
    public static function checkIfExist(): ?ShoppingCartModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM shopping_cart WHERE user_id=:user_id AND order_completed=:order_completed LIMIT 1");
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->bindValue('order_completed', false);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ShoppingCartModel(
            $result->id,
            OrderValidator::generateFromShoppingCart($result->order_id, Users::get($result->user_id)),
            Product::get($result->product_id),
            floatval($result->product_price),
            Color::getByValue($result->color),
            Size::getByValue($result->size),
            new DateTimeImmutable($result->valid_until),
            Users::get($result->user_id),
            new DateTimeImmutable($result->created_at),
            boolval($result->order_completed),
        );
    }

    /**
     * @throws Exception
     */
    public static function get(): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT 
                sc.*,
                sc.id as scid,
                pnt.id as pid
                FROM shopping_cart AS sc
                LEFT JOIN product_name_translation AS pnt ON sc.product_id=pnt.product_id
                WHERE sc.user_id=:user_id AND order_completed=:order_completed AND pnt.locale=:locale");
        $stmt->bindValue('user_id', Session::getUserId());
        $stmt->bindValue('order_completed', 0);
        $stmt->bindValue('locale', 'hr');
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ShoppingCartDto(
                $qResult->scid,
                OrderValidator::generateFromShoppingCart($qResult->order_id, Users::get($qResult->user_id)),
                Product::get($qResult->product_id),
                floatval($qResult->product_price),
                Color::getByValue($qResult->color),
                Size::getByValue($qResult->size),
                new DateTimeImmutable($qResult->valid_until),
                Users::get($qResult->user_id),
                new DateTimeImmutable($qResult->created_at),
                boolval($qResult->order_completed),
                ProductNameTranslation::get($qResult->pid),
                ProductImage::getPrimaryImageByProduct(Product::get($qResult->product_id))
            );
        }

        return $results;
    }

    /**
     * @throws Exception
     */
    public static function getById(string $id): ?ShoppingCartModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM shopping_cart WHERE id=:id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ShoppingCartModel(
            $result->id,
            OrderValidator::generateFromShoppingCart($result->order_id, Users::get($result->user_id)),
            Product::get($result->product_id),
            floatval($result->product_price),
            Color::getByValue($result->color),
            Size::getByValue($result->size),
            new DateTimeImmutable($result->valid_until),
            Users::get($result->user_id),
            new DateTimeImmutable($result->created_at),
            boolval($result->order_completed),
        );
    }

    /**
     * @throws Exception
     */
    private static function getExpiredEntries(): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM shopping_cart WHERE valid_until<NOW() AND order_completed=false");
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ShoppingCartModel(
                $qResult->id,
                OrderValidator::generateFromShoppingCart($qResult->order_id, Users::get($qResult->user_id)),
                Product::get($qResult->product_id),
                floatval($qResult->product_price),
                Color::getByValue($qResult->color),
                Size::getByValue($qResult->size),
                new DateTimeImmutable($qResult->valid_until),
                Users::get($qResult->user_id),
                new DateTimeImmutable($qResult->created_at),
                boolval($qResult->order_completed),
            );
        }

        return $results;
    }

    public static function deleteExpiredEntry(): void
    {
        ProductQuantity::updateReservedNumberAfterExpiredShoppingCart(self::getExpiredEntries());
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM shopping_cart WHERE valid_until<NOW() AND order_completed=false");
        $stmt->execute();
    }

    public static function remove(ShoppingCartModel $shoppingCart): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM shopping_cart WHERE id=:id");
        $stmt->bindValue('id', $shoppingCart->getId());
        $stmt->execute();
    }

    public static function getShoppingCartProductsForCompletedOrder(OrderModel $order): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM shopping_cart WHERE order_id=:order_id");
        $stmt->bindValue('order_id', $order->getOrderId());
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ShoppingCartModel(
                $qResult->id,
                $order,
                Product::get($qResult->product_id),
                floatval($qResult->product_price),
                Color::getByValue($qResult->color),
                Size::getByValue($qResult->size),
                new DateTimeImmutable($qResult->valid_until),
                Users::get($qResult->user_id),
                new DateTimeImmutable($qResult->created_at),
                boolval($qResult->order_completed)
            );
        }
        return $results;
    }
}
