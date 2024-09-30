<?php

declare(strict_types=1);

namespace Validator;

use Model\CategoryModel;
use Model\ColorModel;

class ColorValidator
{
    public static function generateFromRequest(string $id = null): ColorModel
    {
        $modelId = $id ?: \Uuid::generateUuid();
        return new ColorModel($modelId, ucfirst(strtolower(\Request::post('name'))));
    }
}