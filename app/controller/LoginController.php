<?php

declare(strict_types=1);

class LoginController
{
    public function login(): void
    {
        $user = Users::findUser(Request::post('email'));
        if (null !== $user) {
            if (password_verify(Request::post('password'), $user['password'])) {
                $userDetails = Users::findDetailsForUser($user['id']);
                Session::getInstance()->login($user['admin'], $userDetails);
                unset($user);
                header( 'Location:'.App::config('url').'/Dashboard');
            } else {
                unset($user);
                header( 'Location:'.App::config('url').'?m=1');
            }
        } else {
            unset($user);
            header( 'Location:'.App::config('url').'?m=1');
        }
    }

    public function logout(): void
    {
        Session::getInstance()->logout();
        header( 'Location:'.App::config('url'));
    }
}
