<?php

namespace App\Guides\View;

use App\Guides\ProblemFacts;

class ScriptCode
{
    public static function pilih(ProblemFacts $facts): string
    {
        $key = $facts->keyField();
        $keyIndex = LookupData::keyIndex($facts);

        if ($key === null || $keyIndex === null) {
            return "function PilihData() {\n}";
        }

        $lines = [
            'function PilihData() {',
            '    var '.$key.' = document.getElementById("'.$key.'").value;',
            '',
        ];

        foreach (self::branches($facts, $key, $keyIndex) as $line) {
            $lines[] = $line;
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }

    public static function hitung(ProblemFacts $facts): string
    {
        $lines = ['function HitungBayar() {'];

        foreach (self::readLines($facts) as $line) {
            $lines[] = $line;
        }

        foreach ($facts->rules as $rule) {
            $key = is_string($rule['key'] ?? null) ? $rule['key'] : null;
            $expression = is_string($rule['expression'] ?? null) ? $rule['expression'] : null;

            if ($key === null || $expression === null) {
                continue;
            }

            $lines[] = '    var '.$key.' = '.rtrim(trim($expression), ';').';';
            $lines[] = '    document.getElementById("'.$key.'").value = '.$key.';';
            $lines[] = '';
        }

        while ($lines !== [] && end($lines) === '') {
            array_pop($lines);
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }

    /**
     * @return array<int, string>
     */
    private static function branches(ProblemFacts $facts, string $key, int $keyIndex): array
    {
        $derived = self::derivedFields($facts);
        $rows = LookupData::rows($facts);
        $columns = LookupData::columns($facts);
        $lines = [];
        $last = count($rows) - 1;

        foreach ($rows as $index => $row) {
            $value = (string) ($row[$keyIndex] ?? '');

            $lines[] = match (true) {
                $index === 0 => '    if ('.$key.' == "'.$value.'") {',
                $index === $last => '    } else {',
                default => '    } else if ('.$key.' == "'.$value.'") {',
            };

            foreach ($derived as $column) {
                $position = array_search($column, $columns, true);

                if ($position === false) {
                    continue;
                }

                $lines[] = '        document.getElementById("'.$column.'").value = '
                    .LookupData::literal((string) ($row[$position] ?? '')).';';
            }
        }

        if ($lines !== []) {
            $lines[] = '    }';
        }

        return $lines;
    }

    /**
     * @return array<int, string>
     */
    private static function readLines(ProblemFacts $facts): array
    {
        $lines = [];

        foreach (self::usedNames($facts) as $name) {
            $lines[] = '    var '.$name.' = document.getElementById("'.$name.'").value;';
        }

        if ($lines !== []) {
            $lines[] = '';
        }

        return $lines;
    }

    /**
     * @return array<int, string>
     */
    private static function usedNames(ProblemFacts $facts): array
    {
        $ruleKeys = array_map(fn (array $rule) => $rule['key'] ?? null, $facts->rules);
        $names = [];

        foreach ($facts->fields as $field) {
            $name = is_string($field['name'] ?? null) ? $field['name'] : null;

            if ($name === null || in_array($name, $ruleKeys, true)) {
                continue;
            }

            if (LookupData::usedInRules($facts, $name)) {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * @return array<int, string>
     */
    private static function derivedFields(ProblemFacts $facts): array
    {
        $ruleKeys = array_map(fn (array $rule) => $rule['key'] ?? null, $facts->rules);
        $shown = LookupData::formFieldNames($facts);

        return array_values(array_filter(
            LookupData::columns($facts),
            fn (string $column) => $column !== $facts->keyField()
                && ! in_array($column, $ruleKeys, true)
                && in_array($column, $shown, true),
        ));
    }
}
