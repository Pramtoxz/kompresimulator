<?php

namespace App\Guides;

class ColumnTypeGuide
{
    public static function laravelMethod(string $type): string
    {
        return match ($type) {
            'integer' => 'integer',
            'decimal' => 'decimal',
            'date' => 'date',
            'boolean' => 'boolean',
            'text' => 'text',
            default => 'string',
        };
    }

    public static function laravelLine(string $name, string $type, bool $nullable): string
    {
        $method = self::laravelMethod($type);
        $arguments = $method === 'decimal'
            ? "'{$name}', 15, 2"
            : "'{$name}'";

        return "            \$table->{$method}({$arguments})".($nullable ? '->nullable()' : '').';';
    }

    public static function ci4Field(string $name, string $type, bool $nullable): string
    {
        $lines = ["            '{$name}' => ["];

        foreach (self::ci4Options($type) as $key => $value) {
            $lines[] = "                '{$key}' => {$value},";
        }

        if ($nullable) {
            $lines[] = "                'null' => true,";
        }

        $lines[] = '            ],';

        return implode("\n", $lines);
    }

    /**
     * @return array<string, string>
     */
    private static function ci4Options(string $type): array
    {
        return match ($type) {
            'integer' => ["type" => "'INT'", 'constraint' => '11'],
            'decimal' => ["type" => "'DECIMAL'", 'constraint' => "'15,2'"],
            'date' => ["type" => "'DATE'"],
            'boolean' => ["type" => "'BOOLEAN'"],
            'text' => ["type" => "'TEXT'"],
            default => ["type" => "'VARCHAR'", 'constraint' => '255'],
        };
    }

    public static function sqlName(string $type): string
    {
        return match ($type) {
            'integer' => 'int',
            'decimal' => 'decimal',
            'date' => 'date',
            'boolean' => 'boolean',
            'text' => 'text',
            default => 'varchar',
        };
    }

    public static function reason(string $type): string
    {
        return match ($type) {
            'integer' => 'Isinya angka bulat, termasuk uang dan jumlah. Uang jangan dibuat teks, nanti tidak bisa dihitung.',
            'decimal' => 'Isinya angka yang bisa berkoma, misalnya hasil persentase.',
            'date' => 'Isinya tanggal. Jangan dibuat teks supaya bisa diurutkan.',
            'boolean' => 'Isinya cuma dua kemungkinan, ya atau tidak.',
            'text' => 'Isinya tulisan panjang berparagraf, bukan satu baris.',
            default => 'Isinya tulisan satu baris seperti nama orang atau kode. Ini yang dipakai untuk sebagian besar teks.',
        };
    }
}
