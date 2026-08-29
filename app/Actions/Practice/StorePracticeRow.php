<?php

namespace App\Actions\Practice;

use App\Models\Attempt;
use App\Practice\MigrationParseException;
use App\Practice\PracticeSchema;

class StorePracticeRow
{
    public function __construct(private PracticeSchema $schema) {}

    /**
     * @param  array<string, mixed>  $input
     */
    public function handle(Attempt $attempt, array $input): string
    {
        $tables = $this->schema->tablesFor($attempt);

        if ($tables === []) {
            throw new MigrationParseException('Tabel belum dibuat. Jalankan migration lebih dulu.');
        }

        $table = $tables[0];
        $columns = $this->schema->columns($table);

        $values = array_intersect_key($input, array_flip($columns));

        if ($values === []) {
            throw new MigrationParseException('Tidak ada field yang cocok dengan kolom tabel. Samakan atribut name pada form dengan nama kolom.');
        }

        foreach (['created_at', 'updated_at'] as $timestamp) {
            if (in_array($timestamp, $columns, true)) {
                $values[$timestamp] = now();
            }
        }

        $this->schema->insert($attempt, $table, $values);

        return $table;
    }
}
