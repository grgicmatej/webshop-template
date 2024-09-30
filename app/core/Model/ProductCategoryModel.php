<?php

declare(strict_types=1);

namespace Model;

class ProductCategoryModel
{
    public function __construct(private string $id, private ProductModel $product, private CategoryModel $category)
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
     * @return CategoryModel
     */
    public function getCategory(): CategoryModel
    {
        return $this->category;
    }
}
