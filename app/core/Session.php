<?php

declare(strict_types=1);

class Session
{
    public function login($user): void
    {
        $_SESSION['user'] = $user;
        $_SESSION['isLoggedIn'] = true;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        unset($_SESSION['isLoggedIn']);
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['isLoggedIn']);
    }

    public static function startSession($sessionKey, $sessionValue): void
    {
        session_start();
        if(true === isset($_SESSION[$sessionKey])){
            unset($_SESSION[$sessionValue]);
        }
        $_SESSION[$sessionKey]=$sessionValue;
    }

    public static function stopSession($sessionKey): void
    {
        if(true === isset($_SESSION[$sessionKey])){
            unset($_SESSION[$sessionKey]);
        }
    }
}