<?php

declare(strict_types=1);

namespace Validator;

use Model\UserModel;

class UserValidator
{
    public static function generateFromRequest(): UserModel
    {
        return new UserModel(\Session::getUserId(),
            $_POST['email'],
        null,
        false);
    }

    public static function generateNewGuestUser(): UserModel
    {
        return new UserModel(
            \Uuid::generateUuid(),
            null,
            null,
            false
        );
    }
}
