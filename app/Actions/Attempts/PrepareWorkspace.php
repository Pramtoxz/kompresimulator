<?php

namespace App\Actions\Attempts;

use App\Guides\Stubs\WorkspaceStubs;
use App\Models\Attempt;
use App\Models\AttemptFile;
use App\Practice\WorkspaceFiles;

class PrepareWorkspace
{
    public function handle(Attempt $attempt): void
    {
        foreach (WorkspaceFiles::for($attempt->problem->framework) as $file) {
            $stub = WorkspaceStubs::forProblem($attempt->problem, $file['step_key']);

            $existing = $attempt->files()->firstOrCreate(
                ['path' => $file['path']],
                ['step_key' => $file['step_key'], 'content' => $stub],
            );

            $this->fillWhenEmpty($existing, $stub);
        }
    }

    private function fillWhenEmpty(AttemptFile $file, string $stub): void
    {
        if ($stub === '' || trim((string) $file->content) !== '') {
            return;
        }

        $file->update(['content' => $stub]);
    }
}
