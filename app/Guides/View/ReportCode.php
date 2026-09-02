<?php

namespace App\Guides\View;

use App\Enums\Framework;
use App\Guides\ProblemFacts;

class ReportCode
{
    public static function build(Framework $framework, ProblemFacts $facts): string
    {
        $laravel = $framework === Framework::LaravelBlade;
        $names = self::names($facts);

        $lines = [
            '<!DOCTYPE html>',
            '<html lang="id">',
            '',
            '<head>',
            '    <meta charset="UTF-8">',
            '    <title>Laporan</title>',
            '</head>',
            '',
            '<body>',
            '    <h1>Laporan</h1>',
            '    <table border="1">',
            '        <tr>',
        ];

        foreach ($names as $name) {
            $lines[] = '            <th>'.$facts->labelFor($name).'</th>';
        }

        $lines[] = '        </tr>';
        $lines[] = $laravel
            ? '        @foreach ($'.$facts->table.' as $baris)'
            : '        <?php foreach ($'.$facts->table.' as $baris): ?>';
        $lines[] = '        <tr>';

        foreach ($names as $name) {
            $lines[] = $laravel
                ? '            <td>{{ $baris->'.$name.' }}</td>'
                : '            <td><?= $baris['."'".$name."'".'] ?></td>';
        }

        $lines[] = '        </tr>';
        $lines[] = $laravel
            ? '        @endforeach'
            : '        <?php endforeach; ?>';
        $lines[] = '    </table>';
        $lines[] = '</body>';
        $lines[] = '';
        $lines[] = '</html>';

        return implode("\n", $lines);
    }

    /**
     * @return array<int, string>
     */
    private static function names(ProblemFacts $facts): array
    {
        $names = [];

        foreach ($facts->fields as $field) {
            if (is_string($field['name'] ?? null)) {
                $names[] = $field['name'];
            }
        }

        if ($names !== []) {
            return $names;
        }

        return array_values(array_diff($facts->columnNames(), ['id', 'created_at', 'updated_at']));
    }
}
