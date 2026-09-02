<?php

namespace App\Ai;

use App\Enums\Level;

class ProblemVariation
{
    /**
     * @param  array<int, string>  $forbidden
     */
    private function __construct(
        private string $pattern,
        private int $rows,
        private int $threshold,
        private int $percent,
        private int $floor,
        private int $ceiling,
        private array $forbidden,
    ) {}

    /**
     * @param  array<int, string>  $forbidden
     */
    public static function make(Level $level, int $sequence, array $forbidden = []): self
    {
        $patterns = self::patterns($level);
        $percents = [5, 8, 10, 12, 15, 20, 25];
        $floor = random_int(2, 14) * 25000;

        return new self(
            $patterns[$sequence % count($patterns)],
            random_int(3, 5),
            random_int(2, 8),
            $percents[array_rand($percents)],
            $floor,
            $floor * random_int(3, 6),
            $forbidden,
        );
    }

    public function toPrompt(): string
    {
        $lines = [
            'Pola hitung yang wajib dipakai kali ini: '.$this->pattern.'. Jangan memakai pola lain.',
            'Angka berikut sudah ditentukan dan wajib dipakai apa adanya, jangan diganti dengan angka lain:',
            '- Tabel acuan berisi tepat '.$this->rows.' baris.',
            '- Harga satuan di tabel acuan berada di antara '.number_format($this->floor, 0, ',', '.').' dan '.number_format($this->ceiling, 0, ',', '.').', dan tiap baris nilainya berbeda.',
            '- Angka pembanding pada aturan bersyarat adalah '.$this->threshold.'.',
            '- Persentase yang dipakai adalah '.$this->percent.' persen.',
        ];

        if ($this->forbidden !== []) {
            $lines[] = 'Dilarang keras memakai nama berikut di tabel acuan karena sudah pernah dipakai: '.implode(', ', $this->forbidden).'. Pilih nama lain yang masih masuk akal untuk judul skripsinya.';
        }

        return implode("\n", $lines);
    }

    /**
     * @return array<int, string>
     */
    private static function patterns(Level $level): array
    {
        return match ($level) {
            Level::Awal => [
                'total dari harga satuan dikali jumlah',
                'total dari harga satuan dikali lama pemakaian',
                'total dari harga satuan ditambah satu biaya tetap',
            ],
            Level::Menengah => [
                'potongan persen bila jumlah melewati batas tertentu, lalu total',
                'denda per hari bila melewati batas waktu, lalu total',
                'biaya tambahan per unit di atas kuota gratis, lalu total',
                'pajak persen dari subtotal, lalu total',
            ],
            Level::Akhir => [
                'potongan bertingkat menurut tiga rentang jumlah, lalu subtotal, lalu total',
                'harga bertingkat menurut tiga rentang lama pemakaian, lalu potongan, lalu total',
                'denda bertingkat menurut tiga rentang keterlambatan, lalu subtotal, lalu total',
                'uang muka persen, lalu potongan bila memenuhi syarat, lalu sisa bayar',
            ],
        };
    }
}
