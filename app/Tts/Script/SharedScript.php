<?php

namespace App\Tts\Script;

use App\Enums\StepKey;

class SharedScript
{
    /**
     * @return array<int, string>
     */
    public static function for(StepKey $step): array
    {
        return match ($step) {
            StepKey::Model => self::model(),
            StepKey::Controller => self::controller(),
            StepKey::Routes => self::routes(),
            StepKey::Coding => self::coding(),
            StepKey::Done => self::done(),
            StepKey::Install, StepKey::Migration => [],
        };
    }

    /**
     * @return array<int, string>
     */
    private static function model(): array
    {
        return [
            'Model itu perantara antara kodemu dan tabel di database. Tanpa model, kamu harus menulis perintah SQL yang panjang tiap kali menyimpan data. Buat filenya dengan perintah di layar, dan perhatikan betul penulisan namanya.',
            'Filenya sudah dibuatkan beserta isi bawaannya, jadi tugasmu bukan menulis dari nol. Yang perlu kamu pastikan cuma dua. Pertama, nama tabelnya sudah benar. Kedua, daftar kolom yang boleh diisi sudah lengkap. Kolom yang tidak disebut di daftar itu tidak akan tersimpan walaupun formnya terisi.',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function controller(): array
    {
        return [
            'Controller itu yang menangkap data dari form lalu menyerahkannya ke model. Buat filenya dulu dengan perintah di layar. Dua bagian berikutnya semuanya diisi ke file yang sama ini, jadi jangan bikin file baru lagi.',
            'Controller belum tahu model yang tadi kamu buat. Tambahkan satu baris use di bagian atas file, sebaris dengan use yang sudah ada. Kalau baris ini lupa ditulis, nanti muncul pesan class not found begitu tombol Simpan ditekan.',
            'Sekarang isi method penyimpannya. Inilah yang jalan saat tombol Simpan ditekan: dia mengambil semua isian form, lalu memasukkannya ke tabel. Kalau nanti ada kolom yang kosong padahal formnya terisi, periksa lagi daftar kolom di model tadi.',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function routes(): array
    {
        return [
            'Route itu daftar alamat halaman. Bagian ini sering terlupa, padahal tanpa route, controller yang tadi susah payah kamu tulis tidak akan pernah kepanggil. Filenya sudah ada sejak project dibuat dan sudah berisi satu route bawaan. Route bawaan itu jangan dihapus, karena dialah yang menampilkan halaman formmu nanti.',
            'Sekarang tambahkan satu alamat lagi, khusus untuk tombol Simpan. Perhatikan kata post, bukan get. Alamat ini harus sama persis dengan yang kamu tulis di bagian action pada form, kalau beda datanya tidak akan sampai.',
            'Sebelum lanjut, pastikan routenya benar benar terbaca. Perintah ini menampilkan semua alamat yang terdaftar. Cuma sepuluh detik, tapi menyelamatkan banyak waktu. Lebih baik ketahuan salah sekarang daripada nanti saat tombol Simpan ditekan dan halamannya malah not found.',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function coding(): array
    {
        return [
            'Sekarang bagian yang paling banyak diketik. Buka file tampilan bawaannya, blok semua isinya, hapus, lalu ganti dengan form yang ada di layar. Urutan fieldnya ikuti soal dari atas ke bawah, jangan diacak. Yang paling penting, isi name pada tiap input harus sama persis dengan nama kolom di tabel.',
            'Tambahkan tag script tepat sebelum penutup body, lalu tulis fungsi pertama. Fungsi ini jalan tiap dropdown diganti, tugasnya mengisi sendiri field yang nilainya mengikuti pilihan. Tulis angkanya apa adanya tanpa titik pemisah ribuan, karena titik itu akan dibaca sebagai angka desimal.',
            'Masih di dalam tag script yang sama, tambahkan fungsi kedua di bawah fungsi tadi. Fungsi ini jalan tiap angka diketik, tugasnya menghitung lalu mengisi field hasil. Urutan barisnya penting, karena nilai yang dipakai rumus berikutnya harus sudah dihitung lebih dulu di baris atasnya.',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function done(): array
    {
        return [
            'Sebelum ditutup, coba formnya sekali. Pilih satu nilai di dropdown, lalu isi angkanya. Kalau field hasil hitungan terisi sendiri tanpa kamu ketik, berarti rumusmu sudah jalan.',
            'Sekarang tekan Simpan. Satu kali saja sudah cukup untuk membuktikan semuanya nyambung, dari form sampai data yang tersimpan.',
            'Terakhir, dan ini yang paling menentukan di hari ujian. Sebutkan tujuh langkah tadi dalam hati tanpa melihat layar. Install, migrasi, model, controller, routes, ngoding, selesai. Yang bikin gagal itu lupa urutan, bukan lupa syntax.',
        ];
    }
}
