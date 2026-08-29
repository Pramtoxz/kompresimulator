<?php

namespace App\Practice;

use App\Enums\Framework;

class MigrationParser
{
    public function __construct(
        private LaravelMigrationParser $laravel,
        private Ci4MigrationParser $ci4,
    ) {}

    public function parse(string $code, Framework $framework): ParsedMigration
    {
        return match ($framework) {
            Framework::LaravelBlade => $this->laravel->parse($code),
            Framework::Ci4 => $this->ci4->parse($code),
        };
    }
}
