<?php

declare(strict_types=1);

use Model\SettingModel;

class Settings
{
    public static function all(): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM settings");
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new SettingModel(
                $qResult->id,
                $qResult->setting_key,
                $qResult->value,
            );
        }

        return $results;
    }

    public static function create(SettingModel $setting): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO settings (id, setting_key, value) VALUES (:id, :setting_key, :value)");
        $stmt->bindValue('id', $setting->getId());
        $stmt->bindValue('setting_key', $setting->getSettingKey());
        $stmt->bindValue('value', $setting->getValue());
        $stmt->execute();
    }

    public static function update(SettingModel $setting): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE settings SET value=:value WHERE id=:id");
        $stmt->bindValue('value', $setting->getValue());
        $stmt->bindValue('id', $setting->getId());
        $stmt->execute();
    }

    public static function get(string $id): ?SettingModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM settings WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new SettingModel(
            $result->id,
            $result->setting_key,
            $result->value,
        );
    }

    public static function getByKey(string $key): ?SettingModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM settings WHERE setting_key=:key');
        $stmt->bindValue('key', $key);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new SettingModel(
            $result->id,
            $result->setting_key,
            $result->value,
        );
    }

    public static function delete(SettingModel $setting): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('DELETE FROM settings WHERE id=:id');
        $stmt->bindValue('id', $setting->getId());
        $stmt->execute();
    }

}