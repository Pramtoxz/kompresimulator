<?php

namespace App\Enums;

enum StepKey: string
{
    case Install = 'install';
    case Migration = 'migration';
    case Model = 'model';
    case Controller = 'controller';
    case Routes = 'routes';
    case Coding = 'coding';
    case Done = 'done';

    public function number(): int
    {
        return match ($this) {
            self::Install => 1,
            self::Migration => 2,
            self::Model => 3,
            self::Controller => 4,
            self::Routes => 5,
            self::Coding => 6,
            self::Done => 7,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Install => 'Install framework kosong',
            self::Migration => 'Buat migration',
            self::Model => 'Buat model',
            self::Controller => 'Buat controller',
            self::Routes => 'Buat routes',
            self::Coding => 'Ngoding form dan laporan',
            self::Done => 'Selesai',
        };
    }
}
