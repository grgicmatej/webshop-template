<?php

declare(strict_types=1);

class Category
{

    public function __construct(private string $id, private string $image, private bool $active)
    {
    }

    public static function all(): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT category.id, image, active, cnt.name, cnt.locale FROM category LEFT JOIN category_name_translation AS cnt on category.id = cnt.category_id WHERE cnt.locale=:locale");
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

    public static function create($id, $image): void
    {
        $active = 0;
        if (Request::post('active') == 1) {
            $active = 1;
        }

        $db = Db::getInstance();
        $stmt = $db->prepare('INSERT INTO category 
                    (id, image, active) 
                VALUES 
                    (:id, :image, :active)');
        $stmt->bindValue('id', $id);
        $stmt->bindValue('image', $image);
        $stmt->bindValue('active', $active);
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

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }


}