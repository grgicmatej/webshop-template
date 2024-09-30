<?php

declare(strict_types=1);

namespace Validator;

use Model\ColorModel;
use Model\ContentImageModel;
use Model\ContentModel;

class ContentImageValidator
{
    public static function generateFromRequest(string $image = null): ContentImageModel
    {
        return new ContentImageModel(\Request::post('id'), $image, \Request::post('title'), \Request::post('title_en'), (int)\Request::post('page'));
    }
}