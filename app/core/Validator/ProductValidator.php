<?php

declare(strict_types=1);

namespace Validator;

use Model\ProductModel;

class ProductValidator
{
    public static function generateFromRequest(string $id = null): ProductModel
    {
        return new ProductModel(
            $id ?: \Uuid::generateUuid(),
            floatval(\Request::post('price')),
            floatval(\Request::post('price_on_sale')),
            intval(\Request::post('active') === '1'),
            intval(\Request::post('active_sale_price') === '1'),
            intval(false),
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            !empty(\Request::post('sku_number')) ? intval(\Request::post('sku_number')) : null,
            intval(false),
            intval(\Request::post('featured') === '1'),
            0
        );
    }
}
