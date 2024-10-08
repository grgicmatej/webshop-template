<?php

class Request
{
    public static function pathInfo()
    {
        if (isset($_SERVER['PATH_INFO'])) {
            return $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REDIRECT_PATH_INFO'])) {
            return $_SERVER['REDIRECT_PATH_INFO'];
        } else {
            return '';
        }
    }

    public static function post($key, $default='0')
    {
        return $_POST[$key] ?? $default;
    }
}
