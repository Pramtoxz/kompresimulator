<?php

namespace App\Enums;

enum Level: string
{
    case Awal = 'awal';
    case Menengah = 'menengah';
    case Akhir = 'akhir';

    public function label(): string
    {
        return match ($this) {
            self::Awal => 'Level Awal',
            self::Menengah => 'Level Menengah',
            self::Akhir => 'Level Akhir',
        };
    }

    public function showsExampleCode(): bool
    {
        return $this === self::Awal;
    }

    public function showsInstruction(): bool
    {
        return $this !== self::Akhir;
    }

    public function allowsManyProblems(): bool
    {
        return $this === self::Akhir;
    }
}
