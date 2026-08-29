<?php

namespace App\Practice;

enum ColumnType: string
{
    case Identity = 'identity';
    case String = 'string';
    case Text = 'text';
    case Integer = 'integer';
    case BigInteger = 'bigInteger';
    case Decimal = 'decimal';
    case Boolean = 'boolean';
    case Date = 'date';
    case DateTime = 'dateTime';

    public function ddl(): string
    {
        return match ($this) {
            self::Identity => 'bigserial primary key',
            self::String => 'varchar(255)',
            self::Text => 'text',
            self::Integer => 'integer',
            self::BigInteger => 'bigint',
            self::Decimal => 'numeric(15, 2)',
            self::Boolean => 'boolean',
            self::Date => 'date',
            self::DateTime => 'timestamp',
        };
    }

    public static function fromMethod(string $method): ?self
    {
        return match (strtolower($method)) {
            'id', 'increments', 'bigincrements' => self::Identity,
            'string', 'char', 'varchar' => self::String,
            'text', 'longtext', 'mediumtext' => self::Text,
            'integer', 'int', 'unsignedinteger', 'smallinteger', 'tinyinteger' => self::Integer,
            'biginteger', 'unsignedbiginteger' => self::BigInteger,
            'decimal', 'float', 'double', 'unsigneddecimal' => self::Decimal,
            'boolean', 'bool' => self::Boolean,
            'date' => self::Date,
            'datetime', 'timestamp' => self::DateTime,
            default => null,
        };
    }

    public static function fromSqlType(string $type): ?self
    {
        $normalized = strtolower(trim($type));

        return match (true) {
            str_contains($normalized, 'serial') => self::Identity,
            str_contains($normalized, 'varchar'), str_contains($normalized, 'char') => self::String,
            str_contains($normalized, 'text') => self::Text,
            str_contains($normalized, 'bigint') => self::BigInteger,
            str_contains($normalized, 'int') => self::Integer,
            str_contains($normalized, 'decimal'), str_contains($normalized, 'float'), str_contains($normalized, 'double'), str_contains($normalized, 'numeric') => self::Decimal,
            str_contains($normalized, 'bool') => self::Boolean,
            str_contains($normalized, 'datetime'), str_contains($normalized, 'timestamp') => self::DateTime,
            str_contains($normalized, 'date') => self::Date,
            default => null,
        };
    }
}
