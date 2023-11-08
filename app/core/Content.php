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
}