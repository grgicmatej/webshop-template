<?php

declare(strict_types=1);

class SecurityController
{
    public function isAdmin(): void
    {
        if (false === Session::getInstance()->isLoggedIn() || false === Session::getInstance()->isAdmin()) {
            header( 'Location:'.App::config('url'));
        }
    }
}