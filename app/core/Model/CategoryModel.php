<?php

declare(strict_types=1);

namespace Model;

class CategoryModel
{
    public function __construct(private string $id, private string $image, private bool $active)
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
