<?php

declare(strict_types=1);

namespace Validator;

use Model\CategoryModel;

class CategoryValidator extends \Request
{
    private const ID = 'id';
    private const IMAGE = 'image';
    private const ACTIVE = 'active';
    public static function generateFromRequest(string $image): CategoryModel
    {
        $id = \Uuid::generateUuid();

        $active = false;
        if ('1' === $_POST[self::ACTIVE]) {
            $active = true;
        }

        return new CategoryModel($id, $image, $active);
    }
}
