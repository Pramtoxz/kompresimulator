<?php

namespace App\Guides\View;

use App\Enums\Framework;
use App\Guides\ProblemFacts;

class FormCode
{
    public static function build(Framework $framework, ProblemFacts $facts): string
    {
        $laravel = $framework === Framework::LaravelBlade;

        $open = $laravel
            ? '    <form action="{{ url(\'/simpan\') }}" method="POST">'
            : '    <form action="/simpan" method="POST">';

        $lines = [
            '<!DOCTYPE html>',
            '<html lang="id">',
            '',
            '<head>',
            '    <meta charset="UTF-8">',
            '    <meta name="viewport" content="width=device-width, initial-scale=1.0">',
            '    <title>Form Input</title>',
            '</head>',
            '',
            '<body>',
            '    <h1>Form Input</h1>',
            $open,
        ];

        if ($laravel) {
            $lines[] = '        @csrf';
        }

        $lines[] = '        <table>';

        foreach (self::rows($facts) as $row) {
            $lines[] = $row;
        }

        foreach (self::buttons() as $button) {
            $lines[] = $button;
        }

        $lines[] = '        </table>';
        $lines[] = '    </form>';
        $lines[] = '</body>';
        $lines[] = '';
        $lines[] = '</html>';

        return implode("\n", $lines);
    }

    /**
     * @return array<int, string>
     */
    private static function rows(ProblemFacts $facts): array
    {
        $rows = [];

        foreach ($facts->fields as $field) {
            $name = is_string($field['name'] ?? null) ? $field['name'] : null;

            if ($name === null) {
                continue;
            }

            $rows[] = '            <tr>';
            $rows[] = '                <td>'.$facts->labelFor($name).'</td>';
            $rows[] = '                <td>'.self::input($facts, $name, is_string($field['input'] ?? null) ? $field['input'] : 'text').'</td>';
            $rows[] = '            </tr>';
        }

        return $rows;
    }

    /**
     * @return array<int, string>
     */
    private static function buttons(): array
    {
        return [
            '            <tr>',
            '                <td>',
            '                    <button type="submit">Simpan Data</button>',
            '                    <button type="button" onclick="location.href=\'/laporan\'">Laporan</button>',
            '                </td>',
            '            </tr>',
        ];
    }

    private static function input(ProblemFacts $facts, string $name, string $input): string
    {
        $attributes = 'name="'.$name.'" id="'.$name.'"';

        if ($input === 'select') {
            return '<select '.$attributes.' onchange="PilihData()">'."\n"
                .self::options($facts)."\n"
                .'                </select>';
        }

        return match ($input) {
            'readonly' => '<input type="number" '.$attributes.' readonly>',
            'number' => '<input type="number" '.$attributes.' oninput="HitungBayar()">',
            'date' => '<input type="date" '.$attributes.'>',
            default => '<input type="text" '.$attributes.'>',
        };
    }

    private static function options(ProblemFacts $facts): string
    {
        $keyIndex = LookupData::keyIndex($facts);

        if ($keyIndex === null) {
            return '                    <option value="">-</option>';
        }

        $options = [];

        foreach (LookupData::rows($facts) as $row) {
            $value = (string) ($row[$keyIndex] ?? '');
            $options[] = '                    <option value="'.$value.'">'.$value.'</option>';
        }

        return implode("\n", $options);
    }
}
