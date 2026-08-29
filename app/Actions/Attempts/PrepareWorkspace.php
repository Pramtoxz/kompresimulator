<?php

namespace App\Actions\Attempts;

use App\Models\Attempt;
use App\Practice\WorkspaceFiles;

class PrepareWorkspace
{
    public function handle(Attempt $attempt): void
    {
        foreach (WorkspaceFiles::for($attempt->problem->framework) as $file) {
            $attempt->files()->firstOrCreate(
                ['path' => $file['path']],
                ['step_key' => $file['step_key'], 'content' => ''],
            );
        }
    }
}
