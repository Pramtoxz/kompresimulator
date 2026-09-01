<?php

namespace App\Actions\Practice;

use App\Models\Attempt;
use App\Practice\PracticeSchema;
use App\Practice\SpecMigration;

class CreateTableFromSpec
{
    public function __construct(private PracticeSchema $schema) {}

    /**
     * @return array{table: string, columns: array<int, string>}
     */
    public function handle(Attempt $attempt): array
    {
        $parsed = SpecMigration::from($attempt->problem);
        $table = $this->schema->create($attempt, $parsed);

        return ['table' => $table, 'columns' => $this->schema->columns($table)];
    }
}
