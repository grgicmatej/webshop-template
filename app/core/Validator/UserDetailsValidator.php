<?php

declare(strict_types=1);

namespace Validator;

use Model\UserDetailsModel;
use Model\UserModel;

class UserDetailsValidator
{
    public static function generateFromRequest(UserModel $user): UserDetailsModel
    {
        return new UserDetailsModel(
            $user,
            $_POST['name'],
            $_POST['surname'],
            $_POST['address'],
            $_POST['city'],
            $_POST['postal'],
            $_POST['phone'],
            true);
    }
}
