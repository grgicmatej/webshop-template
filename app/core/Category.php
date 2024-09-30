<?php

declare(strict_types=1);

use Dto\CategoryDto;
use Model\CategoryModel;

class Category
{
    public static function all(): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT category.id, image, active, cnt.name, cnt.locale FROM category LEFT JOIN category_name_translation AS cnt on category.id = cnt.category_id WHERE cnt.locale=:locale");
        $stmt->bindValue('locale', 'hr');
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new CategoryDto(
                $qResult->id,
                $qResult->image,
                boolval($qResult->active),
                $qResult->name,
                $qResult->locale
            );
        }

        return $results;
    }

    public static function get(string $id): ?CategoryModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM category WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new CategoryModel(
            $result->id,
            $result->image,
            boolval($result->active),
        );
    }

    public static function create(CategoryModel $categoryModel): void
    {

        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO category 
                    (id, image, active) 
                VALUES 
                    (:id, :image, :active)');
        $stmt->bindValue('id', $categoryModel->getId());
        $stmt->bindValue('image', $categoryModel->getImage());
        $stmt->bindValue('active', $categoryModel->isActive());
        $stmt->execute();
    }

    public static function update(CategoryModel $categoryModel): void
    {
        $db = Db::getInstance();
        if (null === $categoryModel->getImage()) {
            $stmt = $db->prepare('UPDATE category SET active=:active WHERE id=:id');
        } else {
            $stmt = $db->prepare('UPDATE category SET active=:active, image=:image WHERE id=:id');
            $stmt->bindValue('image', $categoryModel->getImage());
        }
        $stmt->bindValue('active', intval($categoryModel->isActive()));
        $stmt->bindValue('id', $categoryModel->getId());
        $stmt->execute();
    }

    public static function delete(CategoryModel $categoryModel): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM category WHERE id=:id");
        $stmt->bindValue('id', $categoryModel->getId());
        $stmt->execute();
    }
}
