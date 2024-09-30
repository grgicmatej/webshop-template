<?php

declare(strict_types=1);

namespace Validator;

use Model\ProductImageModel;
use Model\ProductModel;

class ProductImageValidator
{
    public static function generateFromRequest(string $image, ProductModel $product): ProductImageModel
    {
        return new ProductImageModel(\Uuid::generateUuid(), $product, $image, false);
    }
}