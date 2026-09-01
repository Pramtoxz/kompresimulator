<?php

namespace App\Tts\Script;

class BriefingScript
{
    public const STEP = 'pembuka';

    /**
     * @return array<int, string>
     */
    public static function texts(): array
    {
        return [
            'Sebentar, jangan langsung ngoding. Kita bedah dulu soalnya, biar kamu tahu yang mau dibikin itu apa. Semua isian di form nanti masuk ke satu tabel saja. Satu field di form jadi satu kolom di tabel, jadi jumlahnya sama.',
            'Yang perlu kamu perhatikan, tiap kolom itu punya tipe, dan tipenya tidak asal pilih. Nama orang dan kode itu tulisan, jadi pakai varchar. Uang dan jumlah itu angka bulat, jadi pakai int, jangan dibuat tulisan karena nanti tidak bisa dihitung. Tanggal pakai date. Sekarang lihat daftar kolom soalmu di layar, lalu tekan tombol paham kalau sudah siap.',
        ];
    }
}
