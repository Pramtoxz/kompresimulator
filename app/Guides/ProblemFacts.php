<?php

namespace App\Guides;

use App\Models\Problem;
use Illuminate\Support\Str;

class ProblemFacts
{
    /**
     * @param  array<int, array<string, mixed>>  $columns
     * @param  array<int, array<string, mixed>>  $fields
     * @param  array<int, array<string, mixed>>  $rules
     * @param  array<string, mixed>  $lookup
     */
    private function __construct(
        public string $table,
        public array $columns,
        public array $fields,
        public array $rules,
        public array $lookup,
    ) {}

    public static function from(Problem $problem): self
    {
        $spec = $problem->schema_spec ?? [];

        return new self(
            is_string($spec['table'] ?? null) ? $spec['table'] : 'transaksi',
            is_array($spec['columns'] ?? null) ? array_values($spec['columns']) : [],
            array_values($problem->form_fields ?? []),
            array_values($problem->calc_rules['rules'] ?? []),
            $problem->lookup ?? ['key_field' => null, 'columns' => [], 'rows' => []],
        );
    }

    public function studly(): string
    {
        return Str::studly($this->table);
    }

    public function modelClass(): string
    {
        return 'M'.$this->studly();
    }

    public function controllerClass(): string
    {
        return $this->studly().'Controller';
    }

    public function migrationClass(): string
    {
        return 'Create'.$this->studly().'Table';
    }

    public function migrationName(): string
    {
        return 'create_'.$this->table.'_table';
    }

    public function routeSlug(): string
    {
        return str_replace('_', '-', $this->table);
    }

    /**
     * @return array<int, string>
     */
    public function columnNames(): array
    {
        return array_values(array_filter(array_map(
            fn (array $column) => is_string($column['name'] ?? null) ? $column['name'] : null,
            $this->columns,
        )));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function inputFields(): array
    {
        return array_values(array_filter(
            $this->fields,
            fn (array $field) => ($field['input'] ?? '') !== 'readonly',
        ));
    }

    public function keyField(): ?string
    {
        $key = $this->lookup['key_field'] ?? null;

        return is_string($key) ? $key : null;
    }

    public function totalField(): ?string
    {
        if ($this->rules === []) {
            return null;
        }

        $last = $this->rules[array_key_last($this->rules)];

        return is_string($last['key'] ?? null) ? $last['key'] : null;
    }

    public function labelFor(string $name): string
    {
        foreach ($this->fields as $field) {
            if (($field['name'] ?? null) === $name && is_string($field['label'] ?? null)) {
                return $field['label'];
            }
        }

        return Str::headline($name);
    }
}
