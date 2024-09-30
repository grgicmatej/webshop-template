<?php

declare(strict_types=1);

use Model\ContentImageModel;
use Model\ContentModel;

class Content
{
    public static function all(int $page): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM content WHERE content.page = :page');
        $stmt->bindValue('page', $page);
        $stmt->execute();
        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ContentModel(
                (string) $qResult->id,
                $qResult->text,
                $qResult->text_en,
                (int) $qResult->page
            );
        }

        return $results;
    }

    public static function allImages(int $page): array
    {
        $results = [];
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM content_images WHERE content_images.page = :page');
        $stmt->bindValue('page', $page);
        $stmt->execute();

        $queryResults = $stmt->fetchAll();
        if (true === is_bool($queryResults)) {
            return [];
        }

        foreach ($queryResults as $qResult) {
            $results[] = new ContentImageModel(
                (string) $qResult->id,
                $qResult->image,
                $qResult->title,
                $qResult->title_en,
                (int) $qResult->page,
            );
        }

        return $results;
    }

    public static function get(string $id): ?ContentModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM content WHERE content.id = :id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ContentModel(
            $id,
            $result->text,
            $result->text_en,
            (int) $result->page,
        );
    }

    public static function getOneContentImages(string $id): ?ContentImageModel
    {
        $db = Db::getInstance();
        $stmt = $db->prepare('SELECT * FROM content_images WHERE content_images.id = :id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if (true === is_bool($result)) {
            return null;
        }
        return new ContentImageModel(
            (string) $result->id,
            $result->image,
            $result->title,
            $result->title_en,
            (int) $result->page,
        );
    }

    public static function updateContent(ContentModel $contentModel): void
    {
        $db  = Db::getInstance();
        $stmt = $db->prepare('UPDATE content SET content.text=:text, content.text_en=:text_en WHERE content.id=:id');
        $stmt->bindValue('text', $contentModel->getText());
        $stmt->bindValue('text_en', $contentModel->getTextEn());
        $stmt->bindValue('id', $contentModel->getId());
        $stmt->execute();
    }

    public static function updateContentImages(ContentImageModel $contentImageModel): void
    {
        $db  = Db::getInstance();
        $stmt = $db->prepare('UPDATE content_images SET content_images.image=:image, content_images.title=:title, content_images.title_en=:title_en WHERE content_images.id=:id');
        $stmt->bindValue('image', $contentImageModel->getImage());
        $stmt->bindValue('title', $contentImageModel->getTitle());
        $stmt->bindValue('title_en', $contentImageModel->getTitleEn());
        $stmt->bindValue('id', $contentImageModel->getId());
        $stmt->execute();
    }

    public static function updateContentImageTitle(ContentImageModel $contentImageModel): void
    {
        $db  = Db::getInstance();
        $stmt = $db->prepare('UPDATE content_images SET content_images.title=:title, content_images.title_en=:title_en WHERE content_images.id=:id');
        $stmt->bindValue('title', $contentImageModel->getTitle());
        $stmt->bindValue('title_en', $contentImageModel->getTitleEn());
        $stmt->bindValue('id', $contentImageModel->getId());
        $stmt->execute();
    }
}