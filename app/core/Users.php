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

    public static function setGuestUser($id): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO users (id) VALUES (:id)");
        $stmt->bindValue('id', $id);
        $stmt->execute();

        $stmt = $db->prepare("INSERT INTO users_details (user_id, guest) VALUES (:id, :guest)");
        $stmt->bindValue('id', $id);
        $stmt->bindValue('guest', true);
        $stmt->execute();
    }

    public static function findDetailsForUser(string $id): ?array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM users_details WHERE user_id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $fetchedData = $stmt->fetch();
        if (false !== $fetchedData) {
            return (array) $fetchedData;
        } else {
            return null;
        }
    }

    public static function updateUser(): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE users SET email=:email WHERE id=:id");
        $stmt->bindValue('email', $_POST['email']);
        $stmt->bindValue('id', Session::getUserId());
        $stmt->execute();
    }

    public static function updateUserDetails(): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE users_details SET name=:name, surname=:surname, address=:address, city=:city, postal=:postal, phone=:phone WHERE user_id=:id");
        $stmt->bindValue('name', $_POST['name']);
        $stmt->bindValue('surname', $_POST['surname']);
        $stmt->bindValue('address', $_POST['address']);
        $stmt->bindValue('city', $_POST['city']);
        $stmt->bindValue('postal', $_POST['postal']);
        $stmt->bindValue('phone', $_POST['phone']);
        $stmt->bindValue('id', Session::getUserId());
        $stmt->execute();
    }
}
