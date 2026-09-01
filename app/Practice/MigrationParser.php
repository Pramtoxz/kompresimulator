<?php

namespace App\Practice;

use App\Enums\Framework;

class MigrationParser
{
    public function __construct(private LaravelMigrationParser $laravel) {}

    public function parse(string $code, Framework $framework): ParsedMigration
    {
        if ($framework !== Framework::LaravelBlade) {
            throw new MigrationParseException('Framework ini tidak memakai file migration. Tabelnya dibuat lewat tombol Buat tabel.');
        }

        return $this->laravel->parse($code);
    }
}
