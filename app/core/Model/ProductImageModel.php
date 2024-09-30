<?php

declare(strict_types=1);

namespace Model;

class ProductImageModel
{
    public function __construct(private string $id, private ProductModel $product, private string $image, private bool $primaryImage)
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
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return bool
     */
    public function isPrimaryImage(): bool
    {
        return $this->primaryImage;
    }
}
