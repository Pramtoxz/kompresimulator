<?php

namespace App\Guides\Cards;

use App\Enums\Framework;
use App\Guides\ProblemFacts;
use App\Guides\StepCard;

class ControllerCards
{
    /**
     * @return array<int, StepCard>
     */
    public static function for(Framework $framework, ProblemFacts $facts): array
    {
        $laravel = $framework === Framework::LaravelBlade;

        return [
            self::createFile($laravel, $facts),
            self::importModel($laravel, $facts),
            self::storeMethod($laravel, $facts),
        ];
    }

    private static function createFile(bool $laravel, ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Buat dulu file controllernya',
            'Controller itu yang menangkap data dari form lalu menyerahkannya ke model. Ketik perintah ini di terminal.',
            ($laravel ? 'php artisan make:controller ' : 'php spark make:controller ').$facts->controllerClass(),
            'bash',
            $laravel
                ? 'File barunya muncul di app/Http/Controllers/'.$facts->controllerClass().'.php, berisi class kosong dengan tanda // di dalamnya.'
                : 'File barunya muncul di app/Controllers/'.$facts->controllerClass().'.php, sudah berisi method index yang badannya masih tanda //.',
        );
    }

    private static function importModel(bool $laravel, ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Panggil modelnya di bagian atas',
            'Controller belum tahu model yang tadi kamu buat. Tambahkan satu baris use di bagian atas file, sebaris dengan use yang sudah ada.',
            'use App\Models\\'.$facts->modelClass().';',
            'php',
            'Kalau baris ini lupa ditulis, nanti muncul pesan class not found saat tombol Simpan ditekan.',
        );
    }

    private static function storeMethod(bool $laravel, ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Isi method penyimpannya',
            $laravel
                ? 'Hapus tanda // di dalam class, ganti dengan method ini. Inilah yang jalan saat tombol Simpan ditekan: ambil semua isian form kecuali token, lalu masukkan ke tabel.'
                : 'Di dalam class sudah ada method index bawaan yang badannya cuma tanda //. Biarkan saja, jangan dihapus dan jangan diisi. Tambahkan method baru ini di bawahnya, setelah kurung kurawal penutup method index.',
            $laravel
                ? implode("\n", [
                    '    public function store(Request $request)',
                    '    {',
                    '        $model = new '.$facts->modelClass().'();',
                    "        \$data = \$request->except(['_token']);",
                    '        $model->insert($data);',
                    '',
                    "        return redirect('/');",
                    '    }',
                ])
                : implode("\n", [
                    '    public function store()',
                    '    {',
                    '        $model = new '.$facts->modelClass().'();',
                    '        $model->insert($this->request->getPost());',
                    '',
                    "        return redirect()->to('/');",
                    '    }',
                ]),
            'php',
            $laravel
                ? 'Token itu penanda keamanan yang ikut terkirim dari form, bukan kolom tabel. Kalau tidak dibuang, penyimpanan gagal karena kolomnya tidak ada.'
                : 'Nama kolom di form harus sama persis dengan nama kolom tabel, kalau tidak datanya tidak masuk.',
        );
    }
}
