<?php

declare(strict_types=1);

class Session
{
    private static $instance;

    public function login($isAdmin, $userDetails): void
    {
        $_SESSION['isAdmin'] = $isAdmin;
        $_SESSION['name'] = $userDetails['name'].' '.$userDetails['surname'];
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

    public function isAdmin(): bool
    {
        return isset($_SESSION['isAdmin']);
    }

    public function getName(): string
    {
        return $_SESSION['name'] ?? '';
    }

    public static function getInstance(): Session
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
