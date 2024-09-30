<?php

declare(strict_types=1);

namespace Validator;

use Model\CategoryModel;
use Model\ColorModel;
use Model\ProductCategoryModel;
use Model\ProductModel;
use Model\ProductNameTranslationModel;
use Model\ProductQuantityModel;
use Model\SizeModel;

class ProductQuantityValidator
{
    public static function generateFromRequest(?string $id, ProductModel $productModel, ColorModel $color, SizeModel $size, int $available = 0, int $reserved = 0, int $sold = 0): ProductQuantityModel
    {
        return new ProductQuantityModel($id ?: \Uuid::generateUuid(), $productModel, $color, $size, $available,$reserved,$sold);
    }
}