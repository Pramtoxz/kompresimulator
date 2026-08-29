<?php

namespace App\Enums;

enum Framework: string
{
    case Ci4 = 'ci4';
    case LaravelBlade = 'laravel_blade';

    public function label(): string
    {
        return match ($this) {
            self::Ci4 => 'CodeIgniter 4',
            self::LaravelBlade => 'Laravel Blade',
        };
    }
}
