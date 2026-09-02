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
                ->description('Judul soal singkat, misalnya Transaksi Pemesanan Paket Wisata')
                ->required(),

            'brief' => $schema->string()
                ->description('Narasi soal 2 sampai 4 kalimat dalam bahasa Indonesia')
                ->required(),

            'requirements' => $schema->array()
                ->items($schema->string())
                ->description('Daftar syarat pengerjaan, satu kalimat per syarat')
                ->required(),

            'form_fields' => self::formFields($schema),
            'lookup' => self::lookup($schema),
            'schema_spec' => self::schemaSpec($schema),
            'calc_rules' => self::calcRules($schema),
            'test_cases' => self::testCases($schema),
            'guides' => self::guides($schema),
        ];
    }

    private static function formFields(JsonSchema $schema): Type
    {
        return $schema->array()->items($schema->object([
            'label' => $schema->string()
                ->description('Label yang tampil di form, bahasa Indonesia dengan huruf kapital di awal kata, misalnya Nama Pelanggan')
                ->required(),
            'name' => $schema->string()
                ->description('Nama field dan nama kolom database. Bahasa Indonesia, huruf kecil semua, digabung tanpa spasi dan tanpa garis bawah. Bentuk penulisannya seperti namapelanggan, jumlahitem, totalbayar')
                ->required(),
            'input' => $schema->string()
                ->enum(['text', 'number', 'date', 'select', 'readonly'])
                ->description('select untuk field yang dipilih dari tabel acuan, readonly untuk field hasil hitungan otomatis')
                ->required(),
        ]))
            ->description('Urutan field pada form, mengikuti urutan di kertas soal')
            ->required();
    }

    private static function lookup(JsonSchema $schema): Type
    {
        return $schema->object([
            'key_field' => $schema->string()
                ->description('Nama field bertipe select yang menjadi kunci tabel acuan')
                ->required(),
            'columns' => $schema->array()->items($schema->string())
                ->description('Nama kolom tabel acuan, memakai nama field yang sama dengan form')
                ->required(),
            'rows' => $schema->array()->items($schema->array()->items($schema->string()))
                ->description('Baris tabel acuan. Tiap baris berisi nilai sesuai urutan columns. Angka ditulis tanpa pemisah ribuan')
                ->required(),
        ])
            ->description('Tabel acuan yang dibaca mahasiswa, minimal tiga baris, seperti daftar kode paket di kertas soal')
            ->required();
    }

    private static function schemaSpec(JsonSchema $schema): Type
    {
        return $schema->object([
            'table' => $schema->string()
                ->description('Nama tabel bahasa Indonesia, huruf kecil, satu kata tanpa garis bawah, diturunkan dari judul skripsi')
                ->required(),
            'columns' => $schema->array()->items($schema->object([
                'name' => $schema->string()
                    ->description('Sama persis dengan name pada form_fields')
                    ->required(),
                'type' => $schema->string()
                    ->enum(['string', 'integer', 'decimal', 'date', 'boolean', 'text'])
                    ->required(),
                'nullable' => $schema->boolean()->required(),
            ]))->required(),
        ])->required();
    }

    private static function calcRules(JsonSchema $schema): Type
    {
        return $schema->array()->items($schema->object([
            'key' => $schema->string()
                ->description('Nama field hasil hitungan, sama dengan name pada form_fields, misalnya totalbayar')
                ->required(),
            'description' => $schema->string()
                ->description('Aturan dalam bahasa Indonesia polos seperti di kertas soal. Bentuk kalimatnya seperti: Jika jumlah lebih dari sekian maka potongan sekian persen dari subtotal, selain itu 0')
                ->required(),
            'expression' => $schema->string()
                ->description('Hanya sisi kanan ekspresi JavaScript satu baris tanpa nama variabel di kiri dan tanpa titik koma, memakai nama field lain. Bentuknya seperti: jumlah > 10 ? subtotal * 0.05 : 0')
                ->required(),
        ]))
            ->description('Aturan hitung berurutan. Aturan terakhir wajib menghasilkan nilai akhir yang disimpan')
            ->required();
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
                ->description('Nilai field terakhir pada calc_rules, dihitung benar menurut aturan dan tabel acuan')
                ->required(),
        ]))
            ->description('Tiga test case. Bila soal memakai aturan bersyarat, minimal satu test case memicu syarat itu')
            ->required();
    }

    private static function guides(JsonSchema $schema): Type
    {
        return $schema->array()->items($schema->object([
            'step_key' => $schema->string()
                ->enum(array_column(StepKey::cases(), 'value'))
                ->required(),
            'instruction' => $schema->string()
                ->description('Instruksi langkah dalam bahasa Indonesia sederhana, maksimal 3 kalimat, seolah menuntun orang yang baru pertama kali memakai framework')
                ->required(),
            'example_code' => $schema->string()
                ->description('Kode utuh yang bisa langsung diketik ulang mahasiswa. Pakai baris baru sungguhan, jangan menulis karakter escape backslash n')
                ->required(),
            'tips' => $schema->string()
                ->description('Satu tips singkat agar tidak salah di langkah ini')
                ->required(),
        ]))->required();
    }
}
