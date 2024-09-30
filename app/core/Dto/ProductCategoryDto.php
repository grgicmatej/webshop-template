<?php

declare(strict_types=1);

namespace Dto;

use Model\CategoryModel;
use Model\CategoryNameTranslationModel;
use Model\ProductModel;

class ProductCategoryDto
{
    public function __construct(private int $id, private ProductModel $product, private CategoryModel $category, private CategoryNameTranslationModel $categoryNameTranslation)
    {
    }

    /**
     * @return int
     */
    public function getId(): int
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

    /**
     * @return CategoryNameTranslationModel
     */
    public function getCategoryNameTranslation(): CategoryNameTranslationModel
    {
        return $this->categoryNameTranslation;
    }
}
