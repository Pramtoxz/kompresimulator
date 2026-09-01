<?php

namespace App\Guides\Cards;

use App\Guides\ColumnTypeGuide;
use App\Guides\ProblemFacts;
use App\Guides\StepCard;

class LaravelMigrationCards
{
    private const AUTO_COLUMNS = ['id', 'created_at', 'updated_at'];

    /**
     * @return array<int, StepCard>
     */
    public static function for(ProblemFacts $facts): array
    {
        return [
            self::createFile($facts),
            self::addColumns($facts),
            self::run(),
        ];
    }

    private static function createFile(ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Buat dulu file migrasinya',
            'Jangan bikin file sendiri lewat klik kanan. Ketik perintah ini di terminal, nanti filenya dibuatkan otomatis.',
            'php artisan make:migration '.$facts->migrationName(),
            'bash',
            'File barunya muncul di folder database/migrations. Namanya diawali tanggal dan jam, jadi file yang baru dibuat selalu ada di urutan paling bawah. Itu file yang harus kamu buka.',
        );
    }

    private static function addColumns(ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Sisipkan kolommu di antara id dan timestamps',
            'Buka file paling bawah di database/migrations tadi. Isinya sudah lengkap: pembuka, nama tabel, baris $table->id(), dan baris $table->timestamps() sudah dituliskan Laravel untukmu. Kamu tidak perlu mengetik ulang semuanya. Cukup sisipkan baris di bawah ini tepat di antara baris id dan baris timestamps.',
            self::columnLines($facts),
            'php',
            'Tipe tiap kolom bukan asal pilih. Nama orang pakai string, uang dan jumlah pakai integer, tanggal pakai date.',
        );
    }

    private static function run(): StepCard
    {
        return new StepCard(
            'Jalankan migrasinya',
            'Balik ke terminal, lalu jalankan perintah ini.',
            'php artisan migrate',
            'bash',
            'Kalau databasenya belum ada, Laravel akan bertanya "Would you like to create it?". Tekan Enter saja, dia yang membuatkan. Jadi kamu memang tidak perlu membuat database lewat phpMyAdmin.',
        );
    }

    private static function columnLines(ProblemFacts $facts): string
    {
        $lines = [];

        foreach ($facts->columns as $column) {
            $name = is_string($column['name'] ?? null) ? $column['name'] : null;

            if ($name === null || in_array($name, self::AUTO_COLUMNS, true)) {
                continue;
            }

            $lines[] = ltrim(ColumnTypeGuide::laravelLine(
                $name,
                is_string($column['type'] ?? null) ? $column['type'] : 'string',
                (bool) ($column['nullable'] ?? false),
            ));
        }

        return implode("\n", $lines);
    }
}
