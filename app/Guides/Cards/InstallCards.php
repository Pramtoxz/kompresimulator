<?php

namespace App\Guides\Cards;

use App\Enums\Framework;
use App\Guides\ProblemFacts;
use App\Guides\StepCard;

class InstallCards
{
    /**
     * @return array<int, StepCard>
     */
    public static function for(Framework $framework, ProblemFacts $facts): array
    {
        return match ($framework) {
            Framework::LaravelBlade => self::laravel($facts),
            Framework::Ci4 => self::ci4($facts),
        };
    }

    /**
     * @return array<int, StepCard>
     */
    private static function laravel(ProblemFacts $facts): array
    {
        return [
            new StepCard(
                'Download Laravel kosongnya',
                'Buka terminal di folder tempat kamu mau menaruh project, lalu ketik perintah ini. Tunggu sampai selesai, jangan ditutup di tengah jalan.',
                "composer create-project laravel/laravel latihan\ncd latihan",
                'bash',
                'Kalau terminal masih menampilkan tulisan berjalan, artinya belum selesai. Tunggu sampai muncul lagi tanda siap mengetik.',
            ),
            new StepCard(
                'Buka file .env bawaannya',
                'Di dalam folder latihan sudah ada file bernama .env. Jangan buat file baru. Buka file itu, cari baris yang diawali DB_, lalu ubah isinya jadi seperti ini.',
                "DB_CONNECTION=mysql\nDB_HOST=127.0.0.1\nDB_PORT=3306\nDB_DATABASE=".$facts->table."\nDB_USERNAME=root\nDB_PASSWORD=",
                'ini',
                'Laravel yang baru diunduh bawaannya memakai sqlite, bukan mysql. Kalau baris DB_CONNECTION tidak diganti, nanti migrate jalan tapi tabelnya tidak muncul di phpMyAdmin. Databasenya sendiri belum perlu dibuat sekarang.',
            ),
        ];
    }

    /**
     * @return array<int, StepCard>
     */
    private static function ci4(ProblemFacts $facts): array
    {
        return [
            new StepCard(
                'Download CodeIgniter kosongnya',
                'Buka terminal di folder tempat kamu mau menaruh project, lalu ketik perintah ini. Tunggu sampai selesai, jangan ditutup di tengah jalan.',
                "composer create-project codeigniter4/appstarter latihan\ncd latihan",
                'bash',
                'Kalau terminal masih menampilkan tulisan berjalan, artinya belum selesai. Tunggu sampai muncul lagi tanda siap mengetik.',
            ),
            new StepCard(
                'Ubah file env jadi .env',
                'CodeIgniter membawa file bernama env, tanpa titik di depan. Selama masih begitu, isinya belum terbaca. Salin dulu jadi .env pakai perintah ini.',
                "cp env .env\nphp spark env development",
                'bash',
                'Di Windows tanpa Git Bash, perintah salinnya copy env .env. Titik di depan nama file itu wajib, bukan salah ketik.',
            ),
            new StepCard(
                'Isi pengaturan database di .env',
                'Buka file .env yang barusan jadi, cari baris yang diawali database.default, hapus tanda pagar di depannya, lalu isi seperti ini.',
                "database.default.hostname = localhost\ndatabase.default.database = ".$facts->table."\ndatabase.default.username = root\ndatabase.default.password =\ndatabase.default.DBDriver = MySQLi",
                'ini',
                'Tanda pagar di depan baris artinya baris itu dimatikan. Kalau lupa dihapus, pengaturannya tidak terbaca sama sekali. Databasenya sendiri dibuat di langkah berikutnya lewat SQLyog.',
            ),
        ];
    }
}
