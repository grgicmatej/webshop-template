<?php

declare(strict_types=1);

namespace Model;

class CategoryNameTranslationModel
{
    public function __construct(private string $id, private string $categoryId, private string $locale, private string $name)
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
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}