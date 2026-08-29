<?php

namespace App\Practice;

class ParsedMigration
{
    /**
     * @param  array<int, ParsedColumn>  $columns
     */
    public function __construct(
        public readonly string $table,
        public readonly array $columns,
    ) {}

    /**
     * @return array<int, string>
     */
    public function columnNames(): array
    {
        return array_map(fn (ParsedColumn $column) => $column->name, $this->columns);
    }

    public function hasColumn(string $name): bool
    {
        return in_array($name, $this->columnNames(), true);
    }
}
