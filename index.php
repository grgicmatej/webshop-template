<?php

declare(strict_types=1);

const BP = __DIR__ . DIRECTORY_SEPARATOR;

error_reporting(E_ALL);
ini_set("display_errors", "1");

$t = implode(PATH_SEPARATOR,[
    BP . "app" . DIRECTORY_SEPARATOR . "core",
    BP . "app" . DIRECTORY_SEPARATOR . "controller"
]
);

set_include_path($t);
spl_autoload_register(function($class)
{
    $path = strtr($class,"\\",DIRECTORY_SEPARATOR) . ".php";
    return include $path;
});

App::start();
