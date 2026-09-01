<?php

namespace App\Tts\Script;

use App\Enums\Framework;

class InstallScript
{
    /**
     * @return array<int, string>
     */
    public static function for(Framework $framework): array
    {
        return match ($framework) {
            Framework::LaravelBlade => [
                'Kita mulai dari nol. Buka terminal di folder tempat kamu mau menaruh project, lalu ketik perintah yang ada di layar. Ini mengunduh kerangka Laravel yang masih kosong. Tunggu sampai selesai, jangan ditutup di tengah jalan walaupun terasa lama.',
                'Sekarang buka file titik e n v yang sudah ada di dalam folder project. Jangan bikin file baru. Laravel yang baru diunduh itu bawaannya memakai sqlite, padahal kita mau pakai mysql. Kalau baris ini tidak diganti, nanti migrasinya jalan tapi tabelnya tidak akan muncul di phpMyAdmin. Databasenya sendiri belum perlu dibuat sekarang.',
            ],
            Framework::Ci4 => [
                'Kita mulai dari nol. Buka terminal di folder tempat kamu mau menaruh project, lalu ketik perintah yang ada di layar. Ini mengunduh kerangka CodeIgniter yang masih kosong. Tunggu sampai selesai, jangan ditutup di tengah jalan walaupun terasa lama.',
                'CodeIgniter membawa file bernama e n v, tanpa titik di depan. Selama masih begitu, isinya belum terbaca sama sekali. Jadi salin dulu jadi titik e n v. Titik di depan nama file itu wajib, bukan salah ketik.',
                'Sekarang buka file titik e n v yang barusan jadi. Cari baris pengaturan database, hapus tanda pagar di depannya, lalu isi. Tanda pagar itu artinya baris dimatikan, jadi kalau lupa dihapus, pengaturannya tidak akan terbaca sama sekali.',
            ],
        };
    }
}
