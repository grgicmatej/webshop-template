<?php

declare(strict_types=1);

use Model\CategoryModel;

class Category
{
    public static function all(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT category.id, image, active, cnt.name, cnt.locale FROM category LEFT JOIN category_name_translation AS cnt on category.id = cnt.category_id WHERE cnt.locale=:locale AND category.active=true");
        $stmt->bindValue('locale', 'hr');
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (true === is_bool($result)) {
            return [];
        }
        return $result;
    }

    public static function get($id): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM category WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return [];
        }
        return (array) $result;
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

    public static function update($id, $image): void
    {
        $active = 0;
        if (Request::post('active') == 1) {
            $active = 1;
        }

        $db = Db::getInstance();
        if (null === $image) {
            $stmt = $db->prepare('UPDATE category SET active=:active WHERE id=:id');
        } else {
            $stmt = $db->prepare('UPDATE category SET active=:active, image=:image WHERE id=:id');
            $stmt->bindValue('image', $image);
        }
        $stmt->bindValue('active', $active);
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }
}
