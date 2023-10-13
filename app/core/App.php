<?php

declare(strict_types=1);

final class App
{
    private const CONTROLLER = 'Controller';
    private const INDEX = 'Index';

    public static function start(): void
    {
        session_start();
        $pathInfo = Request::pathInfo();
        $pathInfo = trim($pathInfo, '/');
        $pathParts = explode('/', $pathInfo);

        if (! isset($pathParts[0]) OR empty($pathParts[0])) {
            $controller = self::INDEX;
        } else {
            $controller = ucfirst(strtolower($pathParts[0]));
        }
        $controller .= self::CONTROLLER;

        if (! isset($pathParts[1]) OR empty($pathParts[1])) {
            $action = strtolower(self::INDEX);
        } else {
            $action = strtolower($pathParts[1]);
        }

        if(! isset($pathParts[2]) OR empty($pathParts[2])) {
            $id = '0';
        } else {
            $id = strtolower($pathParts[2]);
        }

        if (class_exists($controller) && method_exists($controller, $action)) {
            $controllerInstance = new $controller();
            if (0 !== intval($id)) {
                $controllerInstance -> $action($id);
            } else {
                $controllerInstance -> $action();
            }
        } else {
            header( 'Location:'.App::config('url').'NotFound');
        }
    }

    public static function config($key)
    {
        $config = include BP . 'app/config.php';
        return $config[$key];
    }
}