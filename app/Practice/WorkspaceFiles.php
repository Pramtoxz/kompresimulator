<?php

namespace App\Practice;

use App\Enums\Framework;
use App\Enums\StepKey;

class WorkspaceFiles
{
    /**
     * @return array<int, array{path: string, step_key: StepKey, language: string}>
     */
    public static function for(Framework $framework): array
    {
        return match ($framework) {
            Framework::LaravelBlade => [
                ['path' => 'database/migrations/create_table.php', 'step_key' => StepKey::Migration, 'language' => 'php'],
                ['path' => 'app/Models/Model.php', 'step_key' => StepKey::Model, 'language' => 'php'],
                ['path' => 'app/Http/Controllers/Controller.php', 'step_key' => StepKey::Controller, 'language' => 'php'],
                ['path' => 'routes/web.php', 'step_key' => StepKey::Routes, 'language' => 'php'],
                ['path' => 'resources/views/form.blade.php', 'step_key' => StepKey::Coding, 'language' => 'blade'],
            ],
            Framework::Ci4 => [
                ['path' => 'app/Database/Migrations/CreateTable.php', 'step_key' => StepKey::Migration, 'language' => 'php'],
                ['path' => 'app/Models/Model.php', 'step_key' => StepKey::Model, 'language' => 'php'],
                ['path' => 'app/Controllers/Controller.php', 'step_key' => StepKey::Controller, 'language' => 'php'],
                ['path' => 'app/Config/Routes.php', 'step_key' => StepKey::Routes, 'language' => 'php'],
                ['path' => 'app/Views/form.php', 'step_key' => StepKey::Coding, 'language' => 'php'],
            ],
        };
    }

    public static function migrationPath(Framework $framework): string
    {
        return self::pathFor($framework, StepKey::Migration);
    }

    public static function viewPath(Framework $framework): string
    {
        return self::pathFor($framework, StepKey::Coding);
    }

    private static function pathFor(Framework $framework, StepKey $step): string
    {
        foreach (self::for($framework) as $file) {
            if ($file['step_key'] === $step) {
                return $file['path'];
            }
        }

        return '';
    }
}
