<?php

declare(strict_types=1);

class Cookie
{
    private const DEFAULT_COOKIE_DURATION = 3600;
    private const DEFAULT_EXPIRED_COOKIE_VALUE = -3600;
    public function setCookie($cookieKey, $cookieValue, $duration = self::DEFAULT_COOKIE_DURATION): void
    {
        setcookie($cookieKey, $cookieValue, $duration);
    }

    public function unsetCookie($cookieKey, $cookieValue): void
    {
        if (true === isset($_COOKIE[$cookieKey])) {
            setcookie($cookieKey, $cookieValue, self::DEFAULT_EXPIRED_COOKIE_VALUE);
        }
    }
}