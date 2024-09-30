<?php

declare(strict_types=1);

namespace Validator;

use Model\CategoryModel;
use Model\ProductCategoryModel;
use Model\ProductModel;

class ProductCategoryValidator
{
    public static function generateFromRequest(ProductModel $product, CategoryModel $category): ProductCategoryModel
    {
        return new ProductCategoryModel(\Uuid::generateUuid(), $product,$category);
    }
}