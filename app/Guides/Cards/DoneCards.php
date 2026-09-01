<?php

namespace App\Guides\Cards;

use App\Enums\Framework;
use App\Guides\ProblemFacts;
use App\Guides\StepCard;

class DoneCards
{
    /**
     * @return array<int, StepCard>
     */
    public static function for(Framework $framework, ProblemFacts $facts): array
    {
        return [
            new StepCard(
                'Coba formnya sekali',
                'Balik sebentar ke langkah ngoding. Di pratinjau, pilih satu nilai di dropdown lalu isi angkanya. Kalau field yang readonly terisi sendiri, berarti rumusmu sudah jalan.',
                null,
                'php',
                $facts->totalField() === null
                    ? 'Yang penting field hasil hitungan tidak kosong.'
                    : 'Yang dilihat penguji pertama kali biasanya '.$facts->labelFor($facts->totalField()).'. Pastikan angkanya keluar.',
            ),
            new StepCard(
                'Tekan Simpan, lalu lihat datanya',
                'Satu kali simpan sudah cukup membuktikan semuanya nyambung: form, tabel, sampai data yang tersimpan. Datanya muncul di bawah pratinjau.',
                null,
                'php',
                'Kalau data masuk tapi ada kolom kosong, penyebabnya hampir selalu daftar kolom di model, bukan formnya.',
            ),
            new StepCard(
                'Ingat urutannya sekali lagi',
                'Sebelum menutup, sebutkan tujuh langkah tadi dalam hati tanpa melihat layar: install, migration, model, controller, routes, ngoding, selesai. Ini yang dinilai di hari ujian, bukan hafalan syntax.',
                null,
                'php',
                'Kalau ada langkah yang tadi jauh lebih lama dari lainnya, itu yang perlu kamu ulang, bukan mengulang semuanya dari nol.',
            ),
        ];
    }
}
