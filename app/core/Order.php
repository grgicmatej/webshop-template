<?php

declare(strict_types=1);

class Order
{
    public static function all(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM orders");
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function getTotalAmounts()
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT SUM(total_price) AS total FROM orders");
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }
}