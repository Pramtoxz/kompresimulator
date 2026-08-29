<?php

namespace App\Practice;

class LaravelMigrationParser
{
    public function parse(string $code): ParsedMigration
    {
        $table = $this->table($code);
        $columns = $this->columns($code);

        if ($columns === []) {
            throw new MigrationParseException('Belum ada kolom yang terbaca. Pastikan ada baris seperti $table->string(\'nama\');');
        }

        return new ParsedMigration($table, $columns);
    }

    private function table(string $code): string
    {
        if (preg_match('/Schema::create\s*\(\s*[\'"]([^\'"]+)[\'"]/i', $code, $match) !== 1) {
            throw new MigrationParseException('Schema::create belum ditemukan. Tulis Schema::create(\'nama_tabel\', function (Blueprint $table) { ... });');
        }

        $table = Identifier::normalize($match[1]);

        if (! Identifier::isValid($table)) {
            throw new MigrationParseException("Nama tabel \"{$match[1]}\" tidak valid. Pakai huruf kecil, angka, dan garis bawah.");
        }

        return $table;
    }

    /**
     * @return array<int, ParsedColumn>
     */
    private function columns(string $code): array
    {
        preg_match_all('/\$table\s*->\s*([a-zA-Z]+)\s*\(([^()]*)\)\s*((?:->\s*[a-zA-Z]+\s*\([^()]*\)\s*)*);/', $code, $matches, PREG_SET_ORDER);

        $columns = [];

        foreach ($matches as $match) {
            $method = $match[1];
            $arguments = $match[2];
            $chain = $match[3];

            foreach ($this->columnsFor($method, $arguments, $chain) as $column) {
                $columns[$column->name] = $column;
            }
        }

        return array_values($columns);
    }

    /**
     * @return array<int, ParsedColumn>
     */
    private function columnsFor(string $method, string $arguments, string $chain): array
    {
        $normalized = strtolower($method);

        if ($normalized === 'timestamps') {
            return [
                new ParsedColumn('created_at', ColumnType::DateTime, true),
                new ParsedColumn('updated_at', ColumnType::DateTime, true),
            ];
        }

        if ($normalized === 'id' && trim($arguments) === '') {
            return [new ParsedColumn('id', ColumnType::Identity)];
        }

        $type = ColumnType::fromMethod($normalized);

        if ($type === null) {
            return [];
        }

        if (preg_match('/[\'"]([^\'"]+)[\'"]/', $arguments, $nameMatch) !== 1) {
            return [];
        }

        $name = Identifier::normalize($nameMatch[1]);

        if (! Identifier::isValid($name)) {
            throw new MigrationParseException("Nama kolom \"{$nameMatch[1]}\" tidak valid. Pakai huruf kecil, angka, dan garis bawah.");
        }

        return [new ParsedColumn($name, $type, str_contains(strtolower($chain), '->nullable('))];
    }
}
