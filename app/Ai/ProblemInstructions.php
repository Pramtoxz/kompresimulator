<?php

namespace App\Ai;

use App\Enums\Framework;
use App\Enums\Level;
use App\Enums\StepKey;

class ProblemInstructions
{
    public const VERSION = 'v6';

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
            'Seluruh label, nama field, nama kolom, dan nama tabel ditulis dalam bahasa Indonesia. Nama field memakai huruf kecil semua dan digabung tanpa spasi maupun garis bawah, bentuknya seperti namapelanggan, jumlahitem, totalbayar. Dilarang memakai garis bawah seperti nama_pelanggan, dan dilarang memakai bahasa Inggris seperti customername atau totalprice. Nama tabel juga satu kata huruf kecil tanpa garis bawah.',
            'Setiap contoh di dalam instruksi ini hanya memperagakan bentuk penulisan, bukan bahan yang boleh disalin. Nama field, isi tabel acuan, seluruh angka, dan tema soal wajib diturunkan dari judul skripsi mahasiswa. Dilarang memakai angka atau nama benda yang muncul di contoh mana pun pada instruksi ini.',
        ]);
    }

    private static function examFormat(): string
    {
        return implode("\n", [
            'Bentuk soal yang wajib diikuti:',
            '1. Satu form input dengan enam sampai delapan field berlabel bahasa Indonesia.',
            '2. Satu field bertipe select yang menjadi kunci tabel acuan. Memilih nilai di field ini mengisi otomatis field turunannya seperti harga atau tarif.',
            '3. Field readonly yang terisi otomatis dari hasil hitungan.',
            '4. Dua tombol tetap: Simpan dan Laporan.',
            '5. Satu tabel acuan berisi tiga sampai lima baris. Isi barisnya diturunkan dari judul skripsi.',
            '6. Aturan hitung ditulis polos dalam bahasa Indonesia, memakai kata jika dan selain itu bila memang bersyarat. Banyaknya aturan, jumlah field, dan bentuknya ditentukan tingkat kesulitan serta pola hitung yang diminta.',
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

    /**
     * @param  array<int, string>  $avoid
     */
    public static function promptFor(
        string $thesisTitle,
        Framework $framework,
        Level $level,
        ProblemVariation $variation,
        array $avoid = [],
    ): string {
        $parts = [
            'Judul skripsi mahasiswa: "'.$thesisTitle.'".',
            'Framework: '.$framework->label().'.',
            self::levelSpec($level),
            $variation->toPrompt(),
        ];

        if ($avoid !== []) {
            $parts[] = "Mahasiswa ini sudah pernah mendapat soal berikut. Soal baru wajib berbeda dari semuanya: beda isi tabel acuan, beda angka, dan beda aturan hitung. Kalau temanya terpaksa sama karena mengikuti judul skripsi, ganti sudut pandangnya.\n- ".implode("\n- ", $avoid);
        }

        $parts[] = 'Susun satu soal ujian mengikuti format kertas soal dosen, dengan tabel acuan dan aturan hitung yang konsepnya mengikuti judul skripsi tersebut.';
        $parts[] = 'Angka pada tabel acuan dan test case harus konsisten satu sama lain.';

        return implode("\n\n", $parts);
    }

    private static function levelSpec(Level $level): string
    {
        $shared = 'Aturan hitung paling banyak tiga, tidak boleh lebih. Pengisian harga otomatis dari tabel acuan saat dropdown dipilih tidak dihitung sebagai aturan hitung.';

        return implode("\n", [...match ($level) {
            Level::Awal => [
                'Tingkat kesulitan: paling ringan, untuk mahasiswa yang baru pertama kali menyentuh framework.',
                'Pakai enam field, satu di antaranya select, dan satu field readonly berisi hasil hitungan.',
                'Cukup satu aturan hitung dan tanpa syarat, jadi tidak ada kata jika di dalam aturannya.',
            ],
            Level::Menengah => [
                'Tingkat kesulitan: sedang.',
                'Pakai tujuh field, satu di antaranya select, dan dua field readonly berisi hasil hitungan.',
                'Buat dua aturan hitung, tepat satu di antaranya bersyarat memakai jika dan selain itu.',
            ],
            Level::Akhir => [
                'Tingkat kesulitan: paling berat, ini simulasi ujian sesungguhnya.',
                'Pakai delapan field, satu di antaranya select, dan tiga field readonly berisi hasil hitungan.',
                'Buat tiga aturan hitung, satu di antaranya bertingkat dengan tiga kemungkinan nilai.',
            ],
        }, $shared]);
    }
}
