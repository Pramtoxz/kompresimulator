<?php

namespace App\Practice;

use App\Models\Attempt;
use Illuminate\Support\Facades\DB;

class PracticeSchema
{
    private const SCHEMA = 'latihan';

    public function tableName(Attempt $attempt, string $table): string
    {
        return $attempt->tablePrefix().$table;
    }

    public function create(Attempt $attempt, ParsedMigration $migration): string
    {
        $table = $this->tableName($attempt, $migration->table);

        $this->guard($table);

        $definitions = array_map(
            fn (ParsedColumn $column) => $column->ddl(),
            $migration->columns,
        );

        DB::statement('drop table if exists '.$this->qualified($table));
        DB::statement('create table '.$this->qualified($table).' ('.implode(', ', $definitions).')');

        return $table;
    }

    /**
     * @return array<int, string>
     */
    public function tablesFor(Attempt $attempt): array
    {
        return DB::table('information_schema.tables')
            ->where('table_schema', self::SCHEMA)
            ->where('table_name', 'like', $attempt->tablePrefix().'%')
            ->orderBy('table_name')
            ->pluck('table_name')
            ->all();
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function insert(Attempt $attempt, string $table, array $values): void
    {
        $this->guard($table);
        $this->guardBelongsTo($attempt, $table);

        DB::table(self::SCHEMA.'.'.$table)->insert($values);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function rows(Attempt $attempt, string $table, int $limit = 50): array
    {
        $this->guard($table);
        $this->guardBelongsTo($attempt, $table);

        return array_map(
            fn (object $row) => (array) $row,
            DB::table(self::SCHEMA.'.'.$table)->limit($limit)->get()->all(),
        );
    }

    /**
     * @return array<int, string>
     */
    public function columns(string $table): array
    {
        $this->guard($table);

        return DB::table('information_schema.columns')
            ->where('table_schema', self::SCHEMA)
            ->where('table_name', $table)
            ->orderBy('ordinal_position')
            ->pluck('column_name')
            ->all();
    }

    public function dropAllFor(Attempt $attempt): void
    {
        foreach ($this->tablesFor($attempt) as $table) {
            DB::statement('drop table if exists '.$this->qualified($table));
        }
    }

    private function qualified(string $table): string
    {
        return '"'.self::SCHEMA.'"."'.$table.'"';
    }

    private function guard(string $table): void
    {
        if (! Identifier::isValid($table)) {
            throw new MigrationParseException("Nama tabel \"{$table}\" tidak valid.");
        }
    }

    private function guardBelongsTo(Attempt $attempt, string $table): void
    {
        if (! str_starts_with($table, $attempt->tablePrefix())) {
            throw new MigrationParseException('Tabel ini bukan milik percobaan yang sedang berjalan.');
        }
    }
}
