<?php

declare(strict_types=1);

use Model\UserDetailsModel;
use Model\UserModel;

class Users
{
    public static function findUser(string $email): ?UserModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM users WHERE email=:email');
        $stmt->bindValue('email', $email);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new UserModel(
            $result->id,
            $result->email,
            $result->password,
            boolval($result->admin)
        );
    }

    public static function get(string $id = null): ?UserModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM users WHERE id=:id');
        $stmt->bindValue('id', $id ?: Session::getUserId());
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new UserModel(
            $result->id,
            $result->email,
            $result->password,
            boolval($result->admin)
        );
    }

    public static function setGuestUser(UserModel $user): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO users (id) VALUES (:id)");
        $stmt->bindValue('id', $user->getId());
        $stmt->execute();

        $stmt = $db->prepare("INSERT INTO users_details (user_id, guest) VALUES (:user_id, :guest)");
        $stmt->bindValue('user_id', $user->getId());
        $stmt->bindValue('guest', true);
        $stmt->execute();
    }

    public static function findDetailsForUser(UserModel $users): ?UserDetailsModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM users_details WHERE user_id=:user_id');
        $stmt->bindValue('user_id', $users->getId());
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new UserDetailsModel(
            $users,
            $result->name,
            $result->surname,
            $result->address,
            $result->city,
            $result->postal,
            $result->phone,
            boolval($result->guest)
        );
    }

    public static function updateUser(UserModel $user): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE users SET email=:email WHERE id=:id");
        $stmt->bindValue('email', $user->getEmail());
        $stmt->bindValue('id', $user->getId());
        $stmt->execute();
    }

    public static function updateUserDetails(UserDetailsModel $userDetails): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE users_details SET name=:name, surname=:surname, address=:address, city=:city, postal=:postal, phone=:phone WHERE user_id=:id");
        $stmt->bindValue('name', $userDetails->getName());
        $stmt->bindValue('surname', $userDetails->getSurname());
        $stmt->bindValue('address', $userDetails->getAddress());
        $stmt->bindValue('city', $userDetails->getCity());
        $stmt->bindValue('postal', $userDetails->getPostal());
        $stmt->bindValue('phone', $userDetails->getPhone());
        $stmt->bindValue('id', $userDetails->getUsers()->getId());
        $stmt->execute();
    }
}
