<?php

declare(strict_types=1);

class SecurityController
{
    private CONST HR = 'hr';
    private CONST EN = 'en';

    public function isAdmin(): void
    {
        if (false === Session::getInstance()->isLoggedIn() || false === Session::getInstance()->isAdmin()) {
            header( 'Location:'.App::config('url'));
        }
    }

    public function getHrLocale(): string
    {
        return self::HR;
    }

    public function getEnLocale(): string
    {
        return self::EN;
    }
}