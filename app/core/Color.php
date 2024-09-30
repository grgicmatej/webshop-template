<?php

declare(strict_types=1);

use Model\ColorModel;

class Color
{
    public static function all(): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM color");
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ColorModel(
                $qResult->id,
                $qResult->name
            );
        }

        return $results;
    }

    public static function get(string $id): ?ColorModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM color WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ColorModel(
            $result->id,
            $result->name
        );
    }

    public static function create(ColorModel $color): bool
    {

        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO color 
                    (id, name) 
                VALUES 
                    (:id, :name)');
        $stmt->bindValue('id', $color->getId());
        $stmt->bindValue('name',$color->getName());
        return $stmt->execute();
    }

    public static function update(ColorModel $color): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE color SET name=:name WHERE id=:id");
        $stmt->bindValue('name', $color->getName());
        $stmt->bindValue('id', $color->getId());
        $stmt->execute();
    }

    public static function delete(ColorModel $color): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM color WHERE id=:id");
        $stmt->bindValue('id', $color->getId());
        $stmt->execute();
    }

    public static function getByValue(string $value): ?ColorModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM color WHERE name=:name');
        $stmt->bindValue('name', $value);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ColorModel(
            $result->id,
            $result->name
        );
    }
}
