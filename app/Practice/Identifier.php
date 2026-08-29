<?php

namespace App\Practice;

class Identifier
{
    public static function isValid(string $name): bool
    {
        return preg_match('/^[a-z][a-z0-9_]{0,50}$/', $name) === 1;
    }

    public static function normalize(string $name): string
    {
        return strtolower(trim($name));
    }
}
