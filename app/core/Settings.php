<?php

declare(strict_types=1);

class Settings
{
    public static function all(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM settings");
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function create(): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO settings (id, setting_key, value) VALUES (:id, :setting_key, :value)");
        $stmt->bindValue('id', Uuid::generateUuid());
        $stmt->bindValue('setting_key', Request::post('setting_key'));
        $stmt->bindValue('value', Request::post('value'));
        $stmt->execute();
    }

    public static function update($id): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE settings SET value=:value WHERE id=:id");
        $stmt->bindValue('value', Request::post('value'));
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    public static function get($id): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM settings WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
    }

    public static function delete($id): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('DELETE FROM settings WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

}