<?php

declare(strict_types=1);

class Content
{
    public static function getContent(int $page): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM content WHERE content.page = :page');
        $stmt->bindValue('page', $page);
        $stmt->execute();

        $fetchedData = $stmt->fetchAll();
        if (false === $fetchedData) {
            return [];
        }

        return $fetchedData;
    }

    public static function getContentImages(int $page): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM content_images WHERE content_images.page = :page');
        $stmt->bindValue('page', $page);
        $stmt->execute();

        $fetchedData = $stmt->fetchAll();
        if (false === $fetchedData) {
            return [];
        }

        return $fetchedData;
    }

    public static function getOneContent(int $id)
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM content WHERE content.id = :id');
        $stmt->bindValue('id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function getOneContentImages(int $id)
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM content_images WHERE content_images.id = :id');
        $stmt->bindValue('id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function updateContent(): void
    {
        $db  = Db::getInstance();
        $stmt = $db->prepare('UPDATE content SET content.text=:text, content.text_en=:text_en WHERE content.id=:id');
        $stmt->bindValue('text', Request::post('text'));
        $stmt->bindValue('text_en', Request::post('text_en'));
        $stmt->bindValue('id', Request::post('id'));
        $stmt->execute();
    }

    public static function updateContentImages(string $image): void
    {
        $db  = Db::getInstance();
        $stmt = $db->prepare('UPDATE content_images SET content_images.image=:image, content_images.title=:title, content_images.title_en=:title_en WHERE content_images.id=:id');
        $stmt->bindValue('image', $image);
        $stmt->bindValue('title', Request::post('title'));
        $stmt->bindValue('title_en', Request::post('title_en'));
        $stmt->bindValue('id', Request::post('id'));
        $stmt->execute();
    }
}