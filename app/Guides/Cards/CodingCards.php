<?php

namespace App\Guides\Cards;

use App\Enums\Framework;
use App\Guides\ProblemFacts;
use App\Guides\StepCard;
use App\Guides\View\FormCode;
use App\Guides\View\ScriptCode;

class CodingCards
{
    /**
     * @return array<int, StepCard>
     */
    public static function for(Framework $framework, ProblemFacts $facts): array
    {
        return [
            self::formSkeleton($framework, $facts),
            self::pilih($facts),
            self::hitung($facts),
        ];
    }

    private static function formSkeleton(Framework $framework, ProblemFacts $facts): StepCard
    {
        $laravel = $framework === Framework::LaravelBlade;

        $path = $laravel
            ? 'resources/views/welcome.blade.php'
            : 'app/Views/welcome_message.php';

        return new StepCard(
            'Timpa isi file tampilan bawaannya',
            'Buka file '.$path.'. Isinya halaman sambutan bawaan yang tidak kita pakai. Blok semuanya, hapus, lalu ganti dengan form di bawah. Urutan fieldnya ikuti soal dari atas ke bawah, jangan diacak.',
            FormCode::build($framework, $facts),
            'html',
            'Isi name pada tiap input harus sama persis dengan nama kolom di tabel. Beda satu huruf, datanya tidak akan tersimpan.',
        );
    }

    private static function pilih(ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Fungsi pertama: mengisi otomatis dari dropdown',
            'Tambahkan tag script tepat sebelum penutup body, lalu tulis fungsi ini di dalamnya. Fungsi ini jalan tiap dropdown diganti, tugasnya mengisi field yang nilainya mengikuti pilihan.',
            "<script>\n".ScriptCode::pilih($facts)."\n</script>",
            'javascript',
            'Angka ditulis apa adanya tanpa titik pemisah ribuan. Menulis 150.000 akan dibaca JavaScript sebagai angka lain.',
        );
    }

    private static function hitung(ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Fungsi kedua: menghitung totalnya',
            'Masih di dalam tag script yang sama, tambahkan fungsi kedua di bawah fungsi tadi. Fungsi ini jalan tiap angka diketik, tugasnya mengisi field hasil hitungan.',
            ScriptCode::hitung($facts),
            'javascript',
            'Urutan barisnya penting. Nilai yang dipakai rumus berikutnya harus sudah dihitung lebih dulu di baris atasnya.',
        );
    }
}
