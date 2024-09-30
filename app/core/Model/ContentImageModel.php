<?php

declare(strict_types=1);

namespace Model;

class ContentImageModel
{
    public function __construct(private string $id, private ?string $image, private string $title, private ?string $titleEn, private int $page)
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
