<?php

declare(strict_types=1);

use Model\SizeModel;

class Size
{
    public static function all(): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM size");
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new SizeModel(
                $qResult->id,
                $qResult->name
            );
        }

        return $results;
    }

    public static function get(string $id): ?SizeModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM size WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new SizeModel(
            $result->id,
            $result->name
        );
    }

    public static function create(SizeModel $size): bool
    {

        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO size 
                    (id, name) 
                VALUES 
                    (:id, :name)');
        $stmt->bindValue('id', $size->getId());
        $stmt->bindValue('name', $size->getName());
        return $stmt->execute();
    }

    public static function update(SizeModel $size): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE size SET name=:name WHERE id=:id");
        $stmt->bindValue('name', $size->getName());
        $stmt->bindValue('id', $size->getId());
        $stmt->execute();
    }

    public static function delete(SizeModel $size): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM size WHERE id=:id");
        $stmt->bindValue('id', $size->getId());
        $stmt->execute();
    }

    public static function getByValue(string $value): ?SizeModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM size WHERE name=:name');
        $stmt->bindValue('name', $value);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new SizeModel(
            $result->id,
            $result->name
        );
    }
}