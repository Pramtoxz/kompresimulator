<?php

namespace App\Guides\Cards;

use App\Guides\ColumnTypeGuide;
use App\Guides\ProblemFacts;
use App\Guides\StepCard;

class SqlyogCards
{
    private const AUTO_COLUMNS = ['id', 'created_at', 'updated_at'];

    /**
     * @return array<int, StepCard>
     */
    public static function for(ProblemFacts $facts): array
    {
        return [
            self::connect(),
            self::createDatabase($facts),
            self::createTable($facts),
        ];
    }

    private static function connect(): StepCard
    {
        return new StepCard(
            'Buka SQLyog, sambungkan ke MySQL',
            'Nyalakan dulu Apache dan MySQL di XAMPP atau Laragon. Baru buka SQLyog, lalu isi kotak sambungannya: Host localhost, User root, Password dikosongkan, Port 3306. Tekan Connect.',
            null,
            'php',
            'Kalau muncul pesan tidak bisa connect, hampir selalu karena MySQL di XAMPP atau Laragon belum dinyalakan. Periksa itu dulu sebelum menyalahkan SQLyog.',
        );
    }

    private static function createDatabase(ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Buat databasenya',
            'Di panel kiri, klik kanan pada nama sambungan, pilih Create Database. Isi namanya persis seperti yang tadi kamu tulis di file .env, lalu tekan Create.',
            $facts->table,
            'ini',
            'Namanya harus sama persis dengan database.default.database di file .env. Beda satu huruf saja, nanti CodeIgniter tidak menemukan databasenya.',
        );
    }

    private static function createTable(ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Buat tabel beserta kolomnya',
            'Klik kanan database yang barusan jadi, pilih Create Table. Isi grid kolomnya satu baris per kolom sesuai daftar di bawah. Baris id dibuat lebih dulu, centang Primary Key dan Auto Increment. Simpan dengan nama tabel '.$facts->table.'.',
            self::columnList($facts),
            'ini',
            'Nama kolom harus sama persis dengan name pada form nanti. Kolom uang dan jumlah pakai INT, jangan VARCHAR, karena nanti dipakai berhitung.',
        );
    }

    private static function columnList(ProblemFacts $facts): string
    {
        $lines = ['id                  INT       Primary Key, Auto Increment'];

        foreach ($facts->columns as $column) {
            $name = is_string($column['name'] ?? null) ? $column['name'] : null;

            if ($name === null || in_array($name, self::AUTO_COLUMNS, true)) {
                continue;
            }

            $type = ColumnTypeGuide::sqlName(
                is_string($column['type'] ?? null) ? $column['type'] : 'string',
            );

            $lines[] = str_pad($name, 20).self::sqlColumn($type);
        }

        return implode("\n", $lines);
    }

    private static function sqlColumn(string $type): string
    {
        return match ($type) {
            'int' => 'INT',
            'decimal' => 'DECIMAL(15,2)',
            'date' => 'DATE',
            'boolean' => 'TINYINT(1)',
            'text' => 'TEXT',
            default => 'VARCHAR(255)',
        };
    }
}
