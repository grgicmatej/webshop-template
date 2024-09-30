<?php

declare(strict_types=1);

#[AllowDynamicProperties]
class LoginController
{
    public function index(): void
    {
        $view = new View();
        $view->render('public/login',
            []);
    }

    public function login(): void
    {
        $user = Users::findUser(Request::post('email'));
        if (null !== $user) {
            if (true === password_verify(Request::post('password'), $user->getPassword())) {
                $userDetails = Users::findDetailsForUser($user);
                Session::getInstance()->login($user, $userDetails);
                unset($user);
                header( 'Location:'.App::config('url').'Dashboard');
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
        session_destroy();
        header( 'Location:'.App::config('url'));
    }
}
