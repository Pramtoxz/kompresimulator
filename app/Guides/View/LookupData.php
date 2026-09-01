<?php

namespace App\Guides\View;

use App\Guides\ProblemFacts;

class LookupData
{
    /**
     * @return array<int, string>
     */
    public static function columns(ProblemFacts $facts): array
    {
        $columns = $facts->lookup['columns'] ?? [];

        return is_array($columns) ? array_values(array_filter($columns, 'is_string')) : [];
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function rows(ProblemFacts $facts): array
    {
        $rows = $facts->lookup['rows'] ?? [];

        return is_array($rows) ? array_values(array_filter($rows, 'is_array')) : [];
    }

    public static function keyIndex(ProblemFacts $facts): ?int
    {
        $index = array_search($facts->keyField(), self::columns($facts), true);

        return $index === false ? null : $index;
    }

    /**
     * @return array<int, string>
     */
    public static function formFieldNames(ProblemFacts $facts): array
    {
        return array_values(array_filter(array_map(
            fn (array $field) => is_string($field['name'] ?? null) ? $field['name'] : null,
            $facts->fields,
        )));
    }

    public static function usedInRules(ProblemFacts $facts, string $name): bool
    {
        foreach ($facts->rules as $rule) {
            $expression = is_string($rule['expression'] ?? null) ? $rule['expression'] : '';

            if (preg_match('/\b'.preg_quote($name, '/').'\b/', $expression) === 1) {
                return true;
            }
        }

        return false;
    }

    public static function literal(string $value): string
    {
        return is_numeric($value) ? $value : "'".$value."'";
    }

    public static function heading(ProblemFacts $facts): string
    {
        return ucwords(str_replace('_', ' ', $facts->table));
    }
}
