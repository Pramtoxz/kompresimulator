<?php

namespace App\Practice;

use App\Models\Problem;

class SpecMigration
{
    public static function from(Problem $problem): ParsedMigration
    {
        $spec = $problem->schema_spec ?? [];
        $table = Identifier::normalize(is_string($spec['table'] ?? null) ? $spec['table'] : '');

        if (! Identifier::isValid($table)) {
            throw new MigrationParseException('Soal ini belum punya nama tabel yang sah. Minta admin menggenerate ulang soalnya.');
        }

        $columns = [new ParsedColumn('id', ColumnType::Identity)];

        foreach (is_array($spec['columns'] ?? null) ? $spec['columns'] : [] as $column) {
            $parsed = self::column($column);

            if ($parsed !== null && $parsed->name !== 'id') {
                $columns[] = $parsed;
            }
        }

        if (count($columns) === 1) {
            throw new MigrationParseException('Soal ini belum punya daftar kolom. Minta admin menggenerate ulang soalnya.');
        }

        return new ParsedMigration($table, $columns);
    }

    /**
     * @param  mixed  $column
     */
    private static function column($column): ?ParsedColumn
    {
        if (! is_array($column)) {
            return null;
        }

        $name = Identifier::normalize(is_string($column['name'] ?? null) ? $column['name'] : '');

        if (! Identifier::isValid($name)) {
            return null;
        }

        return new ParsedColumn(
            $name,
            self::type(is_string($column['type'] ?? null) ? $column['type'] : 'string'),
            (bool) ($column['nullable'] ?? false),
        );
    }

    private static function type(string $type): ColumnType
    {
        return match ($type) {
            'integer' => ColumnType::Integer,
            'decimal' => ColumnType::Decimal,
            'date' => ColumnType::Date,
            'boolean' => ColumnType::Boolean,
            'text' => ColumnType::Text,
            default => ColumnType::String,
        };
    }
}
