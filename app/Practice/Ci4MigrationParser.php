<?php

namespace App\Practice;

class Ci4MigrationParser
{
    public function parse(string $code): ParsedMigration
    {
        $table = $this->table($code);
        $columns = $this->columns($code);

        if ($columns === []) {
            throw new MigrationParseException('Belum ada kolom yang terbaca. Pastikan addField berisi definisi kolom.');
        }

        return new ParsedMigration($table, $columns);
    }

    private function table(string $code): string
    {
        if (preg_match('/createTable\s*\(\s*[\'"]([^\'"]+)[\'"]/i', $code, $match) !== 1) {
            throw new MigrationParseException('createTable belum ditemukan. Tulis $this->forge->createTable(\'nama_tabel\');');
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
        if (preg_match('/addField\s*\(\s*\[(.*?)\]\s*\)\s*;/s', $code, $block) !== 1) {
            return [];
        }

        preg_match_all(
            '/[\'"]([a-zA-Z0-9_]+)[\'"]\s*=>\s*\[(.*?)\]/s',
            $block[1],
            $matches,
            PREG_SET_ORDER,
        );

        $columns = [];

        foreach ($matches as $match) {
            $name = Identifier::normalize($match[1]);
            $options = $match[2];

            if (! Identifier::isValid($name)) {
                throw new MigrationParseException("Nama kolom \"{$match[1]}\" tidak valid. Pakai huruf kecil, angka, dan garis bawah.");
            }

            $column = $this->column($name, $options);

            if ($column !== null) {
                $columns[$name] = $column;
            }
        }

        if (preg_match('/addKey\s*\(\s*[\'"]([a-zA-Z0-9_]+)[\'"]\s*,\s*true/i', $code, $key) === 1) {
            $primary = Identifier::normalize($key[1]);

            if (isset($columns[$primary])) {
                $columns[$primary] = new ParsedColumn($primary, ColumnType::Identity);
            }
        }

        return array_values($columns);
    }

    private function column(string $name, string $options): ?ParsedColumn
    {
        if (preg_match('/[\'"]type[\'"]\s*=>\s*[\'"]([^\'"]+)[\'"]/i', $options, $typeMatch) !== 1) {
            return null;
        }

        $type = ColumnType::fromSqlType($typeMatch[1]);

        if ($type === null) {
            return null;
        }

        if (preg_match('/[\'"]auto_increment[\'"]\s*=>\s*true/i', $options) === 1) {
            $type = ColumnType::Identity;
        }

        $nullable = preg_match('/[\'"]null[\'"]\s*=>\s*true/i', $options) === 1;

        return new ParsedColumn($name, $type, $nullable);
    }
}
