<?php

namespace App\Actions\Practice;

use App\Models\Attempt;
use App\Practice\MigrationParser;
use App\Practice\PracticeSchema;
use App\Practice\WorkspaceFiles;

class RunWorkspaceMigration
{
    public function __construct(
        private MigrationParser $parser,
        private PracticeSchema $schema,
    ) {}

    /**
     * @return array{table: string, columns: array<int, string>}
     */
    public function handle(Attempt $attempt): array
    {
        $framework = $attempt->problem->framework;
        $path = WorkspaceFiles::migrationPath($framework);
        $code = (string) $attempt->files()->where('path', $path)->value('content');

        $parsed = $this->parser->parse($code, $framework);
        $table = $this->schema->create($attempt, $parsed);

        return ['table' => $table, 'columns' => $this->schema->columns($table)];
    }
}
