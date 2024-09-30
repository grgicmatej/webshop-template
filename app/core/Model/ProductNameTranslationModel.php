<?php

declare(strict_types=1);

namespace Model;

class ProductNameTranslationModel
{

    public function __construct(private string $id, private ProductModel $product, private string $locale, private string $name)
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
     * @return ProductModel
     */
    public function getProduct(): ProductModel
    {
        return $this->product;
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
