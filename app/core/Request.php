<?php

declare(strict_types=1);

class Request
{
    public static function pathInfo(): string
    {
        if (isset($_SERVER['PATH_INFO'])) {
            return $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REDIRECT_PATH_INFO'])) {
            return $_SERVER['REDIRECT_PATH_INFO'];
        } else {
            return '';
        }
    }
}