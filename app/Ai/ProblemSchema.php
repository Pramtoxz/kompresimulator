<?php

namespace App\Ai;

use App\Enums\StepKey;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;

class ProblemSchema
{
    /**
     * @return array<string, Type>
     */
    public static function definition(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()
                ->description('Judul soal yang singkat dan spesifik')
                ->required(),

            'brief' => $schema->string()
                ->description('Narasi soal dalam 3 sampai 5 kalimat, menjelaskan apa yang harus dibuat mahasiswa')
                ->required(),

            'requirements' => $schema->array()
                ->items($schema->string())
                ->description('Daftar syarat yang harus dipenuhi, satu kalimat per syarat')
                ->required(),

            'schema_spec' => self::schemaSpec($schema),
            'calc_rules' => self::calcRules($schema),
            'rate_table' => self::rateTable($schema),
            'test_cases' => self::testCases($schema),
            'guides' => self::guides($schema),
        ];
    }

    private static function schemaSpec(JsonSchema $schema): Type
    {
        return $schema->object([
            'table' => $schema->string()
                ->description('Nama tabel dalam bahasa Inggris, huruf kecil, bentuk jamak')
                ->required(),
            'columns' => $schema->array()->items($schema->object([
                'name' => $schema->string()->required(),
                'type' => $schema->string()
                    ->description('Tipe kolom migration, misalnya string, integer, decimal, boolean, date')
                    ->required(),
                'nullable' => $schema->boolean()->required(),
            ]))->required(),
        ])->required();
    }

    private static function calcRules(JsonSchema $schema): Type
    {
        return $schema->array()->items($schema->object([
            'key' => $schema->string()
                ->description('Nama variabel dalam snake_case, misalnya subtotal, discount, tax, total_price')
                ->required(),
            'description' => $schema->string()->required(),
            'expression' => $schema->string()
                ->description('Hanya sisi kanan ekspresi JavaScript satu baris, tanpa nama variabel dan tanda sama dengan di kiri, tanpa titik koma. Boleh memakai nama kolom dan key aturan lain. Dilarang berisi prosa. Contoh yang benar: duration_days >= 5 ? subtotal * 0.1 : 0')
                ->required(),
        ]))->required();
    }

    private static function rateTable(JsonSchema $schema): Type
    {
        return $schema->array()->items($schema->object([
            'key' => $schema->string()
                ->description('Key aturan yang memakai tarif ini, misalnya base_rate')
                ->required(),
            'option' => $schema->string()
                ->description('Nilai pilihan yang menentukan tarif, misalnya SUV')
                ->required(),
            'amount' => $schema->number()
                ->description('Nominal dalam rupiah, angka murni tanpa pemisah ribuan')
                ->required(),
        ]))->required();
    }

    private static function testCases(JsonSchema $schema): Type
    {
        return $schema->array()->items($schema->object([
            'label' => $schema->string()->required(),
            'inputs' => $schema->array()->items($schema->object([
                'field' => $schema->string()->required(),
                'value' => $schema->string()->required(),
            ]))->required(),
            'expected_total' => $schema->number()
                ->description('Total akhir yang benar menurut calc_rules dan rate_table')
                ->required(),
        ]))->required();
    }

    private static function guides(JsonSchema $schema): Type
    {
        return $schema->array()->items($schema->object([
            'step_key' => $schema->string()
                ->enum(array_column(StepKey::cases(), 'value'))
                ->required(),
            'instruction' => $schema->string()
                ->description('Instruksi langkah dalam bahasa Indonesia, maksimal 3 kalimat')
                ->required(),
            'example_code' => $schema->string()
                ->description('Kode utuh yang bisa langsung diketik ulang mahasiswa, bukan sekadar perintah terminal. Pakai baris baru sungguhan, jangan menulis karakter escape backslash n')
                ->required(),
            'tips' => $schema->string()
                ->description('Satu tips singkat agar tidak salah di langkah ini')
                ->required(),
        ]))->required();
    }
}
