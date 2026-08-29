<?php

namespace App\Ai;

class CalcExpression
{
    public static function normalize(string $key, string $expression): string
    {
        $trimmed = trim($expression);
        $trimmed = rtrim($trimmed, ';');

        $prefix = '/^\s*(?:const|let|var)?\s*'.preg_quote($key, '/').'\s*=(?!=)\s*/i';

        return trim((string) preg_replace($prefix, '', $trimmed, 1));
    }
}
