<?php

namespace App\Ai;

use App\Enums\Framework;
use App\Enums\StepKey;

class ProblemInstructions
{
    public const VERSION = 'v1';

    public static function for(Framework $framework): string
    {
        $steps = collect(StepKey::cases())
            ->map(fn (StepKey $step) => $step->number().'. '.$step->value.' — '.$step->label())
            ->implode("\n");

        return implode("\n\n", [
            'Kamu penyusun soal ujian komprehensif untuk mahasiswa sistem informasi di Indonesia.',
            'Mahasiswa mengerjakan di tempat dalam waktu maksimal 30 menit, memakai framework kosong yang mereka install sendiri, editor teks polos tanpa autocomplete, dan tanpa akses internet. Kemampuan pemrograman mereka sangat terbatas.',
            'Karena itu soal harus kecil dan lugas: satu tabel, satu form input, satu kalkulasi otomatis, dan satu halaman laporan sederhana. Dilarang meminta autentikasi, relasi antar tabel, upload berkas, atau grafik.',
            'Framework yang dipakai: '.$framework->label().'.',
            self::frameworkNotes($framework),
            "Panduan wajib berisi tepat tujuh langkah dengan step_key berikut, berurutan:\n".$steps,
            'Aturan kalkulasi harus berupa ekspresi JavaScript satu baris yang bisa dievaluasi mesin. Tarif yang berbeda per pilihan ditulis di rate_table sebagai angka, bukan di dalam ekspresi.',
            'Buat tiga test case dengan angka yang benar-benar dihitung menurut aturan kalkulasi dan tarif yang kamu tetapkan sendiri. Salah satu test case harus memicu diskon atau aturan bersyarat.',
            'Seluruh teks yang dibaca mahasiswa ditulis dalam bahasa Indonesia. Nama tabel dan nama kolom ditulis dalam bahasa Inggris.',
        ]);
    }

    private static function frameworkNotes(Framework $framework): string
    {
        return match ($framework) {
            Framework::Ci4 => implode("\n", [
                'Contoh kode mengikuti CodeIgniter 4:',
                'migration dibuat dengan php spark make:migration dan memakai $this->forge->addField serta $this->forge->createTable.',
                'model mewarisi CodeIgniter\Model dengan properti $table, $allowedFields, dan $useTimestamps.',
                'controller mewarisi BaseController dan mengembalikan view() dari folder app/Views.',
                'route ditulis di app/Config/Routes.php memakai $routes->get dan $routes->post.',
                'tampilan memakai file PHP biasa di app/Views, bukan Blade.',
            ]),
            Framework::LaravelBlade => implode("\n", [
                'Contoh kode mengikuti Laravel dengan Blade:',
                'migration dibuat dengan php artisan make:migration dan memakai Schema::create beserta Blueprint.',
                'model mewarisi Illuminate\Database\Eloquent\Model dengan properti $fillable.',
                'controller mewarisi Controller dan mengembalikan view() dari folder resources/views.',
                'route ditulis di routes/web.php memakai Route::get dan Route::post.',
                'tampilan memakai Blade dengan ekstensi .blade.php.',
            ]),
        };
    }

    public static function promptFor(string $thesisTitle, Framework $framework): string
    {
        return implode("\n", [
            'Judul skripsi mahasiswa: "'.$thesisTitle.'".',
            'Framework: '.$framework->label().'.',
            'Susun satu soal ujian yang konsepnya mengikuti judul skripsi tersebut dan bisa diselesaikan dalam 30 menit.',
        ]);
    }
}
