<?php

namespace App\Practice\Checks;

use App\Enums\CheckKind;
use App\Enums\Framework;
use App\Enums\StepKey;
use App\Models\Attempt;
use App\Models\AttemptFile;

class StructureCheck
{
    /**
     * @return array<int, CheckOutcome>
     */
    public function run(Attempt $attempt): array
    {
        $framework = $attempt->problem->framework;

        return [
            $this->check($attempt, StepKey::Model, $this->modelRules($framework), 'Model'),
            $this->check($attempt, StepKey::Controller, $this->controllerRules($framework), 'Controller'),
            $this->check($attempt, StepKey::Routes, $this->routeRules($framework), 'Routes'),
        ];
    }

    /**
     * @param  array<string, string>  $rules
     */
    private function check(Attempt $attempt, StepKey $step, array $rules, string $label): CheckOutcome
    {
        $content = (string) $attempt->files
            ->first(fn (AttemptFile $file) => $file->step_key === $step)?->content;

        if (trim($content) === '') {
            return new CheckOutcome(CheckKind::Structure, false, "{$label} masih kosong.");
        }

        $missing = [];

        foreach ($rules as $description => $pattern) {
            if (preg_match($pattern, $content) !== 1) {
                $missing[] = $description;
            }
        }

        if ($missing !== []) {
            return new CheckOutcome(
                CheckKind::Structure,
                false,
                "{$label} belum lengkap: ".implode(', ', $missing).'.',
            );
        }

        return new CheckOutcome(CheckKind::Structure, true, "{$label} sudah sesuai.");
    }

    /**
     * @return array<string, string>
     */
    private function modelRules(Framework $framework): array
    {
        return match ($framework) {
            Framework::LaravelBlade => [
                'deklarasi class' => '/class\s+\w+\s+extends\s+Model/i',
                'properti $table' => '/\$table\s*=/',
                'properti $fillable' => '/\$fillable\s*=/',
            ],
            Framework::Ci4 => [
                'deklarasi class' => '/class\s+\w+\s+extends\s+Model/i',
                'properti $table' => '/\$table\s*=/',
                'properti $allowedFields' => '/\$allowedFields\s*=/',
            ],
        };
    }

    /**
     * @return array<string, string>
     */
    private function controllerRules(Framework $framework): array
    {
        return match ($framework) {
            Framework::LaravelBlade => [
                'deklarasi class' => '/class\s+\w+\s+extends\s+Controller/i',
                'pemanggilan model' => '/use\s+App\S*Models\S*\s*;/i',
                'perintah simpan data' => '/(::create\s*\(|->create\s*\(|->save\s*\(|->insert\s*\()/i',
            ],
            Framework::Ci4 => [
                'deklarasi class' => '/class\s+\w+\s+extends\s+(BaseController|Controller)/i',
                'pemanggilan model' => '/use\s+App\S*Models\S*\s*;/i',
                'perintah simpan data' => '/(->save\s*\(|->insert\s*\()/i',
            ],
        };
    }

    /**
     * @return array<string, string>
     */
    private function routeRules(Framework $framework): array
    {
        return match ($framework) {
            Framework::LaravelBlade => [
                'route GET' => '/Route::get\s*\(/i',
                'route POST' => '/Route::post\s*\(/i',
            ],
            Framework::Ci4 => [
                'route GET' => '/\$routes->get\s*\(/i',
                'route POST' => '/\$routes->post\s*\(/i',
            ],
        };
    }
}
