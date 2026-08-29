<?php

namespace App\Ai;

class CodeBlock
{
    public static function normalize(string $code): string
    {
        if (str_contains($code, "\n")) {
            return $code;
        }

        return str_replace(['\r\n', '\n', '\t'], ["\n", "\n", '    '], $code);
    }
}
