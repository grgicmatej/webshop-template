<?php

declare(strict_types=1);

namespace Validator;

use Enum\SettingKeyEnum;
use Model\SettingModel;

class SettingValidator
{
    public static function generateFromRequest(string $id = null): SettingModel
    {
        $modelId = $id ?: \Uuid::generateUuid();
        if (strtolower(SettingKeyEnum::getIbanKey()) === strtolower(\Request::post('key'))) {
            return new SettingModel($modelId, strtolower(\Request::post('key')), strtoupper(\Request::post('value')));
        }
        return new SettingModel($modelId, strtolower(\Request::post('key')), strtolower(\Request::post('value')));
    }
}
