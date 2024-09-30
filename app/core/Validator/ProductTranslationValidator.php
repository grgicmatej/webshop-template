<?php

declare(strict_types=1);

namespace Validator;

use Model\ProductModel;
use Model\ProductTranslationModel;

class ProductTranslationValidator
{
    public static function generateFromRequest(?string $id, ProductModel $productModel, string $locale, string $description): ProductTranslationModel
    {
        return new ProductTranslationModel($id ?: \Uuid::generateUuid(), $productModel, $locale, $description);
    }
}