<?php

namespace App\Practice;

use App\Enums\Framework;
use App\Enums\StepKey;
use App\Guides\ProblemFacts;

class TerminalCommands
{
    /**
     * @return array<int, array{command: string, output: string}>
     */
    public static function for(Framework $framework, StepKey $step, ProblemFacts $facts): array
    {
        return $framework === Framework::LaravelBlade
            ? self::laravel($step, $facts)
            : self::ci4($step, $facts);
    }

    /**
     * @return array<int, array{command: string, output: string}>
     */
    private static function laravel(StepKey $step, ProblemFacts $facts): array
    {
        return match ($step) {
            StepKey::Install => [
                self::line('composer create-project laravel/laravel latihan', 'Application ready! Build something amazing.'),
                self::line('cd latihan', ''),
            ],
            StepKey::Migration => [
                self::line(
                    'php artisan make:migration '.$facts->migrationName(),
                    'INFO  Migration [database/migrations/2026_01_01_000000_'.$facts->migrationName().'.php] created successfully.',
                ),
                self::line('php artisan migrate', 'INFO  Running migrations.'.PHP_EOL.PHP_EOL.'  '.$facts->migrationName().' .......................... DONE'),
            ],
            StepKey::Model => [
                self::line(
                    'php artisan make:model '.$facts->modelClass(),
                    'INFO  Model [app/Models/'.$facts->modelClass().'.php] created successfully.',
                ),
            ],
            StepKey::Controller => [
                self::line(
                    'php artisan make:controller '.$facts->controllerClass(),
                    'INFO  Controller [app/Http/Controllers/'.$facts->controllerClass().'.php] created successfully.',
                ),
            ],
            StepKey::Routes => [
                self::line('php artisan route:list', 'GET|HEAD   /  ....................................... welcome'.PHP_EOL.'POST       simpan ......... '.$facts->controllerClass().'@simpan'.PHP_EOL.'GET|HEAD   laporan ........ '.$facts->controllerClass().'@laporan'),
            ],
            default => [],
        };
    }

    /**
     * @return array<int, array{command: string, output: string}>
     */
    private static function ci4(StepKey $step, ProblemFacts $facts): array
    {
        return match ($step) {
            StepKey::Install => [
                self::line('composer create-project codeigniter4/appstarter latihan', 'Application ready! Build something amazing.'),
                self::line('cd latihan', ''),
                self::line('cp env .env', ''),
                self::line('php spark env development', 'Environment is set to development.'),
            ],
            StepKey::Model => [
                self::line(
                    'php spark make:model '.$facts->modelClass(),
                    'File created: APPPATH\\Models\\'.$facts->modelClass().'.php',
                ),
            ],
            StepKey::Controller => [
                self::line(
                    'php spark make:controller '.$facts->controllerClass(),
                    'File created: APPPATH\\Controllers\\'.$facts->controllerClass().'.php',
                ),
            ],
            StepKey::Routes => [
                self::line('php spark routes', 'GET     /  ................................ \\Home::index'.PHP_EOL.'POST    simpan ......... '.$facts->controllerClass().'::simpan'.PHP_EOL.'GET     laporan ........ '.$facts->controllerClass().'::laporan'),
            ],
            default => [],
        };
    }

    /**
     * @return array{command: string, output: string}
     */
    private static function line(string $command, string $output): array
    {
        return ['command' => $command, 'output' => $output];
    }
}
