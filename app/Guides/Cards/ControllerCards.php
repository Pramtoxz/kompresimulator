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
            self::simpanMethod($laravel, $facts),
            self::laporanMethod($laravel, $facts),
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

    private static function simpanMethod(bool $laravel, ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Isi method penyimpannya',
            $laravel
                ? 'Hapus tanda // di dalam class, ganti dengan method ini. Cuma satu baris isinya: ambil semua isian form, langsung simpan. Inilah yang jalan saat tombol Simpan ditekan.'
                : 'Di dalam class sudah ada method index bawaan yang badannya cuma tanda //. Biarkan saja, jangan dihapus dan jangan diisi. Tambahkan method baru ini di bawahnya, setelah kurung kurawal penutup method index.',
            $laravel
                ? implode("\n", [
                    '    public function simpan(Request $request)',
                    '    {',
                    '        '.$facts->modelClass().'::create($request->all());',
                    '',
                    "        return redirect('/');",
                    '    }',
                ])
                : implode("\n", [
                    '    public function simpan()',
                    '    {',
                    '        $model = new '.$facts->modelClass().'();',
                    '        $model->insert($this->request->getPost());',
                    '',
                    "        return redirect()->to('/');",
                    '    }',
                ]),
            'php',
            $laravel
                ? 'create hanya menyimpan kolom yang kamu sebut di fillable pada model tadi, jadi token keamanan dari form ikut terbuang sendiri. Kalau ada kolom kosong padahal formnya terisi, periksa fillable, bukan formnya.'
                : 'Nama kolom di form harus sama persis dengan nama kolom tabel, kalau tidak datanya tidak masuk.',
        );
    }

    private static function laporanMethod(bool $laravel, ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Tambahkan method laporannya',
            'Tombol Laporan di form mengarah ke halaman lain yang isinya daftar semua data. Tambahkan method kedua ini di bawah method simpan tadi, masih di file yang sama.',
            $laravel
                ? implode("\n", [
                    '    public function laporan()',
                    '    {',
                    '        $'.$facts->table.' = '.$facts->modelClass().'::all();',
                    '',
                    "        return view('laporan', compact('".$facts->table."'));",
                    '    }',
                ])
                : implode("\n", [
                    '    public function laporan()',
                    '    {',
                    '        $model = new '.$facts->modelClass().'();',
                    '        $'.$facts->table.' = $model->findAll();',
                    '',
                    "        return view('laporan', compact('".$facts->table."'));",
                    '    }',
                ]),
            'php',
            'Nama di dalam compact harus sama dengan nama variabel di barisnya, tanpa tanda dolar. Nama itu juga yang nanti kamu pakai di halaman laporan.',
        );
    }
}
