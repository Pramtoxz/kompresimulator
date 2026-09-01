<?php

namespace App\Ai;

use App\Enums\Framework;
use App\Enums\StepKey;

class ProblemInstructions
{
    public const VERSION = 'v3';

    public static function for(Framework $framework): string
    {
        $steps = collect(StepKey::cases())
            ->map(fn (StepKey $step) => $step->number().'. '.$step->value.' — '.$step->label())
            ->implode("\n");

        return implode("\n\n", [
            'Kamu penyusun soal ujian komprehensif di kampus Indonesia. Soal ditulis meniru kertas soal dosen yang formatnya selalu sama.',
            self::examFormat(),
            'Mahasiswa mengerjakan di tempat dalam waktu maksimal 30 menit memakai framework kosong, editor teks polos, dan tanpa internet. Kemampuan pemrograman mereka sangat terbatas, jadi soal harus kecil: satu tabel, satu form, satu kalkulasi otomatis, satu halaman laporan. Dilarang meminta login, relasi antar tabel, unggah berkas, atau grafik.',
            'Framework yang dipakai: '.$framework->label().'.',
            self::frameworkNotes($framework),
            "Panduan wajib berisi tepat tujuh langkah dengan step_key berikut, berurutan:\n".$steps,
            'Panduan ditulis untuk orang yang baru pertama kali menyentuh framework. Sebutkan nama berkas dan letaknya, lalu kode utuh yang bisa langsung diketik ulang. Hindari istilah yang tidak dijelaskan.',
            'Seluruh label, nama field, nama kolom, dan nama tabel ditulis dalam bahasa Indonesia. Nama field memakai huruf kecil semua dan digabung tanpa spasi maupun garis bawah, misalnya namapelanggan, namamobil, hargasewa, lamasewa, potongan, totalbayar, sisabayar. Dilarang memakai garis bawah seperti nama_pelanggan, dan dilarang memakai bahasa Inggris seperti customername atau totalprice. Nama tabel juga satu kata huruf kecil tanpa garis bawah, misalnya sewa atau pemesanan.',
        ]);
    }

    private static function examFormat(): string
    {
        return implode("\n", [
            'Bentuk soal yang wajib diikuti:',
            '1. Satu form input dengan enam sampai delapan field berlabel bahasa Indonesia.',
            '2. Satu field bertipe select yang menjadi kunci, misalnya Kode Paket atau Nama Paket. Memilih nilai di field ini mengisi otomatis field turunannya seperti harga.',
            '3. Beberapa field readonly yang terisi otomatis dari hasil hitungan, misalnya Potongan, Total, atau Sisa Bayar.',
            '4. Dua tombol tetap: Simpan dan Laporan.',
            '5. Satu tabel acuan berisi minimal tiga baris, misalnya avanza 150000, brio 200000, fortuner 300000.',
            '6. Aturan hitung bersyarat yang ditulis polos, misalnya: jika lamasewa lebih dari 3 maka potongan 10 persen dari hargasewa, selain itu 0. Totalbayar sama dengan hargasewa dikali lamasewa dikurangi potongan.',
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
            'Susun satu soal ujian mengikuti format kertas soal dosen, dengan tabel acuan dan aturan hitung yang konsepnya mengikuti judul skripsi tersebut.',
            'Angka pada tabel acuan dan test case harus konsisten satu sama lain.',
        ]);
    }
}
