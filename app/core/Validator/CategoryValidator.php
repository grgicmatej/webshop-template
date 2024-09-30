<?php

declare(strict_types=1);

namespace Validator;

use Model\CategoryModel;

class CategoryValidator extends \Request
{
    private const ID = 'id';
    private const IMAGE = 'image';
    private const ACTIVE = 'active';
    public static function generateFromRequest(?string $image, string $id = null): CategoryModel
    {
        $modelId = $id ?: \Uuid::generateUuid();
        $active = \Request::post('active') === '1';
        return new CategoryModel($modelId, $image, $active);
    }
}
