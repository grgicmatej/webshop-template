<?php

declare(strict_types=1);

namespace Model;

class ContentModel
{
    public function __construct(private string $id, private string $text, private ?string $textEn, private int $page)
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
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getTextEn(): ?string
    {
        return $this->textEn;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
