<?php

declare(strict_types=1);

class Users
{
    public static function findUser(string $email): ?array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM users WHERE email=:email');
        $stmt->bindValue('email', $email);
        $stmt->execute();
        $fetchedData = $stmt->fetch();
        if (false !== $fetchedData) {
            return (array) $fetchedData;
        } else {
            return null;
        }
    }

    public static function findDetailsForUser(string $id): ?array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM users_details WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $fetchedData = $stmt->fetch();
        if (false !== $fetchedData) {
            return (array) $fetchedData;
        } else {
            return null;
        }
    }
}
