<?php

namespace App\Guides\Cards;

use App\Enums\Framework;
use App\Guides\ProblemFacts;
use App\Guides\StepCard;

class RoutesCards
{
    /**
     * @return array<int, StepCard>
     */
    public static function for(Framework $framework, ProblemFacts $facts): array
    {
        return [
            self::openFile($framework),
            self::writeRoutes($framework, $facts),
            self::verify($framework),
        ];
    }

    private static function openFile(Framework $framework): StepCard
    {
        $laravel = $framework === Framework::LaravelBlade;
        $path = $laravel ? 'routes/web.php' : 'app/Config/Routes.php';

        $existing = $laravel
            ? "Route::get('/', function () {\n    return view('welcome');\n});"
            : "\$routes->get('/', 'Home::index');";

        return new StepCard(
            'Buka file routes',
            'Route itu daftar alamat halaman. Tanpa route, controller yang tadi kamu tulis tidak pernah kepanggil. Buka file '.$path.'. Filenya sudah ada sejak project dibuat dan sudah berisi satu route bawaan seperti ini.',
            $existing,
            'php',
            'Route bawaan itu jangan dihapus. Dialah yang menampilkan halaman formmu nanti, karena formnya kamu tulis di file tampilan bawaan itu juga.',
        );
    }

    private static function writeRoutes(Framework $framework, ProblemFacts $facts): StepCard
    {
        $laravel = $framework === Framework::LaravelBlade;

        return new StepCard(
            'Tambahkan alamat untuk Simpan dan Laporan',
            $laravel
                ? 'Tulis baris ini. Baris use ditaruh di bagian atas bersama use yang sudah ada, dua baris route ditaruh di bawah route bawaan tadi.'
                : 'Tulis dua baris ini tepat di bawah route bawaan tadi.',
            $laravel
                ? self::laravelRoutes($facts)
                : self::ci4Routes($facts),
            'php',
            'Perhatikan bedanya: simpan pakai post karena mengirim data, laporan pakai get karena cuma membuka halaman. Alamat simpan harus sama persis dengan yang kamu tulis di bagian action pada form.',
        );
    }

    private static function verify(Framework $framework): StepCard
    {
        return new StepCard(
            'Pastikan routenya terbaca',
            'Balik ke terminal dan jalankan perintah ini. Kalau alamat simpan tadi muncul di daftar, berarti sudah benar.',
            $framework === Framework::LaravelBlade
                ? 'php artisan route:list'
                : 'php spark routes',
            'bash',
            'Langkah ini sepuluh detik tapi menyelamatkan banyak waktu. Lebih baik ketahuan salah sekarang daripada saat tombol Simpan ditekan dan halamannya not found.',
        );
    }

    private static function laravelRoutes(ProblemFacts $facts): string
    {
        $controller = $facts->controllerClass();

        return implode("\n", [
            'use App\Http\Controllers\\'.$controller.';',
            '',
            "Route::post('/simpan', [".$controller."::class, 'simpan']);",
            "Route::get('/laporan', [".$controller."::class, 'laporan']);",
        ]);
    }

    private static function ci4Routes(ProblemFacts $facts): string
    {
        $controller = $facts->controllerClass();

        return implode("\n", [
            "\$routes->post('/simpan', '".$controller."::simpan');",
            "\$routes->get('/laporan', '".$controller."::laporan');",
        ]);
    }
}
