<?php

declare(strict_types=1);

namespace Validator;

use Model\CategoryModel;
use Model\ProductCategoryModel;
use Model\ProductModel;
use Model\ProductNameTranslationModel;

class ProductNameTranslationValidator
{
    public static function generateFromRequest(?string $id, ProductModel $productModel, string $locale, string $name): ProductNameTranslationModel
    {
        return new ProductNameTranslationModel($id ?: \Uuid::generateUuid(), $productModel, $locale, $name);
    }
}