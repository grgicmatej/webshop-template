<?php

declare(strict_types=1);

namespace Validator;


use Model\CategoryNameTranslationModel;

class CategoryNameTranslationValidator extends \Request
{
    private const ID = 'id';
    private const IMAGE = 'image';
    private const ACTIVE = 'active';
    public static function generateFromRequest(string $categoryId, string $locale, string $name): CategoryNameTranslationModel
    {
        return new CategoryNameTranslationModel(\Uuid::generateUuid(), $categoryId, $locale, $name);
    }
}
