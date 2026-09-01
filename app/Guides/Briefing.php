<?php

namespace App\Guides;

use App\Models\Problem;

class Briefing
{
    /**
     * @return array<string, mixed>
     */
    public static function for(Problem $problem): array
    {
        $facts = ProblemFacts::from($problem);

        return [
            'title' => $problem->title,
            'brief' => $problem->brief,
            'table' => $facts->table,
            'key_field_label' => $facts->keyField() === null
                ? null
                : $facts->labelFor($facts->keyField()),
            'total_field_label' => $facts->totalField() === null
                ? null
                : $facts->labelFor($facts->totalField()),
            'columns' => self::columns($facts),
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private static function columns(ProblemFacts $facts): array
    {
        $seen = [];
        $rows = [];

        foreach ($facts->columns as $column) {
            $name = is_string($column['name'] ?? null) ? $column['name'] : null;
            $type = is_string($column['type'] ?? null) ? $column['type'] : 'string';

            if ($name === null || in_array($name, ['id', 'created_at', 'updated_at'], true)) {
                continue;
            }

            $row = [
                'label' => $facts->labelFor($name),
                'name' => $name,
                'sql' => ColumnTypeGuide::sqlName($type),
                'reason' => in_array($type, $seen, true) ? '' : ColumnTypeGuide::reason($type),
            ];

            $seen[] = $type;
            $rows[] = $row;
        }

        return $rows;
    }
}
