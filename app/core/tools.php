<?php

declare(strict_types=1);

class tools
{
    public static function debug(mixed $value): void
    {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
        die();
    }

}