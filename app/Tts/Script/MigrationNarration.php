<?php

namespace App\Tts\Script;

use App\Enums\Framework;

class MigrationNarration
{
    /**
     * @return array<int, string>
     */
    public static function for(Framework $framework): array
    {
        return match ($framework) {
            Framework::LaravelBlade => [
                'Migrasi itu cara membuat tabel lewat kode. Buat dulu filenya dengan perintah di layar, jangan bikin file sendiri lewat klik kanan. File barunya muncul di folder database garis miring migrations. Namanya diawali tanggal dan jam, jadi file yang baru selalu ada di urutan paling bawah. Itu yang harus kamu buka.',
                'Sekarang buka file paling bawah itu. Isinya sudah lengkap. Pembukanya, nama tabelnya, baris id, dan baris timestamps semuanya sudah dituliskan Laravel untukmu. Kamu tidak perlu mengetik ulang semuanya. Cukup sisipkan kolom kolommu tepat di antara baris id dan baris timestamps.',
                'Balik ke terminal, lalu jalankan migrasinya. Kalau databasenya belum ada, Laravel akan bertanya apakah mau dibuatkan. Tekan Enter saja, dia yang membuatkan. Jadi kamu memang tidak perlu repot membuat database lewat phpMyAdmin.',
            ],
            Framework::Ci4 => [
                'Untuk CodeIgniter, tabelnya kita buat manual lewat SQLyog, bukan lewat perintah. Nyalakan dulu Apache dan MySQL, baru buka SQLyog. Isi kotak sambungannya: host localhost, user root, passwordnya dikosongkan, lalu tekan Connect.',
                'Di panel sebelah kiri, klik kanan pada nama sambungan, lalu pilih Create Database. Isi namanya persis seperti yang tadi kamu tulis di file titik e n v. Beda satu huruf saja, nanti CodeIgniter tidak menemukan databasenya.',
                'Sekarang klik kanan database yang barusan jadi, pilih Create Table. Isi gridnya satu baris untuk satu kolom, sesuai daftar yang ada di layar. Baris id dibuat paling dulu, lalu centang primary key dan auto increment. Kolom uang dan jumlah pakai INT, jangan VARCHAR, karena nanti dipakai berhitung.',
            ],
        };
    }
}
