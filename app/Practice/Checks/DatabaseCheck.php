<?php

namespace App\Practice\Checks;

use App\Enums\CheckKind;
use App\Models\Attempt;
use App\Practice\PracticeSchema;

class DatabaseCheck
{
    public function __construct(private PracticeSchema $schema) {}

    /**
     * @return array<int, CheckOutcome>
     */
    public function run(Attempt $attempt): array
    {
        $tables = $this->schema->tablesFor($attempt);

        if ($tables === []) {
            return [new CheckOutcome(
                CheckKind::Database,
                false,
                'Tabel belum dibuat. Jalankan migration lebih dulu.',
            )];
        }

        $table = $tables[0];
        $actual = $this->schema->columns($table);
        $expected = $this->expectedColumns($attempt);
        $missing = array_values(array_diff($expected, $actual));

        if ($missing !== []) {
            return [new CheckOutcome(
                CheckKind::Database,
                false,
                'Kolom belum ada: '.implode(', ', $missing).'.',
                null,
                ['table' => $table, 'columns' => $actual],
            )];
        }

        return [new CheckOutcome(
            CheckKind::Database,
            true,
            'Tabel '.$table.' sudah punya semua kolom yang diminta.',
            null,
            ['table' => $table, 'columns' => $actual],
        )];
    }

    /**
     * @return array<int, string>
     */
    private function expectedColumns(Attempt $attempt): array
    {
        $columns = $attempt->problem->schema_spec['columns'] ?? [];

        return array_values(array_filter(array_map(
            fn (array $column) => $column['name'] ?? null,
            $columns,
        )));
    }
}
