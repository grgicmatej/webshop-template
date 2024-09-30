<?php

declare(strict_types=1);

namespace Validator;

use Model\ColorModel;
use Model\ContentModel;

class ContentValidator
{
    public static function generateFromRequest(): ContentModel
    {
        return new ContentModel(\Request::post('id'), \Request::post('text'), \Request::post('text_en'), (int) \Request::post('page'));
    }
}