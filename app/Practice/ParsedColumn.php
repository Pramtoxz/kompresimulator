<?php

namespace App\Practice;

class ParsedColumn
{
    public function __construct(
        public readonly string $name,
        public readonly ColumnType $type,
        public readonly bool $nullable = false,
    ) {}

    public function ddl(): string
    {
        if ($this->type === ColumnType::Identity) {
            return "\"{$this->name}\" ".$this->type->ddl();
        }

        return "\"{$this->name}\" ".$this->type->ddl().($this->nullable ? '' : ' not null');
    }
}
