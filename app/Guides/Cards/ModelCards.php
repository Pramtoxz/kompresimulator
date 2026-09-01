<?php

namespace App\Guides\Cards;

use App\Enums\Framework;
use App\Guides\ProblemFacts;
use App\Guides\StepCard;
use App\Guides\Stubs\Ci4Stubs;

class ModelCards
{
    /**
     * @return array<int, StepCard>
     */
    public static function for(Framework $framework, ProblemFacts $facts): array
    {
        return [
            self::createFile($framework, $facts),
            $framework === Framework::LaravelBlade
                ? self::laravelFill($facts)
                : self::ci4Fill($facts),
        ];
    }

    private static function createFile(Framework $framework, ProblemFacts $facts): StepCard
    {
        $perintah = $framework === Framework::LaravelBlade
            ? 'php artisan make:model '
            : 'php spark make:model ';

        return new StepCard(
            'Buat dulu file modelnya',
            'Model itu perantara antara kodemu dan tabel. Ketik perintah ini di terminal, jangan bikin file sendiri. Perhatikan huruf M di depan namanya, itu penanda model.',
            $perintah.$facts->modelClass(),
            'bash',
            'File barunya muncul di app/Models/'.$facts->modelClass().'.php.',
        );
    }

    private static function laravelFill(ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Ganti tanda // dengan tiga baris ini',
            'Buka file model tadi. Isinya sudah ada pembuka, namespace, dan class yang mewarisi Model, tapi badannya masih berisi tanda // saja. Hapus tanda // itu, ganti dengan tiga baris di bawah.',
            implode("\n", [
                "    protected \$table = '".$facts->table."';",
                "    protected \$primaryKey = 'id';",
                '    protected $fillable = ['.self::inlineColumns($facts).'];',
            ]),
            'php',
            'Kolom yang tidak kamu sebut di fillable tidak akan tersimpan walaupun formnya terisi. Ini penyebab paling sering data masuk tapi kolomnya kosong.',
        );
    }

    private static function ci4Fill(ProblemFacts $facts): StepCard
    {
        return new StepCard(
            'Betulkan dua baris saja, sisanya biarkan',
            'Buka file model tadi. Isinya panjang, ada puluhan baris properti bawaan. Jangan dihapus dan jangan ditulis ulang. Kamu cuma perlu membetulkan dua baris: baris $table yang namanya salah karena ditambahi huruf s, dan baris $allowedFields yang masih kosong.',
            implode("\n", [
                "    protected \$table            = '".$facts->table."';",
                '    protected $allowedFields    = ['.self::inlineColumns($facts).'];',
            ]),
            'php',
            'Bawaannya tertulis '.Ci4Stubs::defaultTable($facts).', padahal tabelmu bernama '.$facts->table.'. Kolom yang tidak disebut di allowedFields tidak akan tersimpan.',
        );
    }

    private static function inlineColumns(ProblemFacts $facts): string
    {
        $names = array_values(array_diff($facts->columnNames(), ['id', 'created_at', 'updated_at']));

        return implode(', ', array_map(fn (string $name) => "'".$name."'", $names));
    }
}
