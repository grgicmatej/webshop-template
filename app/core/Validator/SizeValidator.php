<?php

declare(strict_types=1);

namespace Validator;

use Model\CategoryModel;
use Model\ColorModel;
use Model\SizeModel;

class SizeValidator
{
    public static function generateFromRequest(string $id = null): SizeModel
    {
        $modelId = $id ?: \Uuid::generateUuid();
        return new SizeModel($modelId, ucfirst(strtoupper(\Request::post('name'))));
    }
}