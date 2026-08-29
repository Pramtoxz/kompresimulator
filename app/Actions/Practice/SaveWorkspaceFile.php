<?php

namespace App\Actions\Practice;

use App\Models\Attempt;
use App\Models\AttemptFile;

class SaveWorkspaceFile
{
    public function handle(Attempt $attempt, string $path, string $content): AttemptFile
    {
        $file = $attempt->files()->where('path', $path)->firstOrFail();

        $file->update(['content' => $content]);

        return $file;
    }
}
