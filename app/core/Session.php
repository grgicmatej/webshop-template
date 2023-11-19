<?php

declare(strict_types=1);

class Session
{
    private static $instance;
    private const UID = 'uid';

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

    public static function getUserId(): string
    {
        return self::get(self::getUidKey());
    }

    public static function getInstance(): Session
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key): string
    {
        return $_SESSION[$key];
    }

    public static function unset($key): void
    {
        unset($_SESSION[$key]);
    }

    public static function checkIfSessionIsSet($key): void
    {
        if (false === isset($_SESSION[$key])) {
            self::set(self::UID, Uuid::generateUuid());
            Users::setGuestUser(self::get(self::UID));
        }
    }

    public static function getUidKey(): string
    {
        return self::UID;
    }
}
