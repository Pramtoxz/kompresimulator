<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** @var string[] */
    protected array $schemas = ['auth', 'ai', 'system', 'latihan'];

    public function up(): void
    {
        $this->dropSchemas();

        foreach ($this->schemas as $schema) {
            DB::statement("CREATE SCHEMA IF NOT EXISTS \"{$schema}\"");
        }
    }

    public function down(): void
    {
        $this->dropSchemas();
    }

    protected function dropSchemas(): void
    {
        foreach ($this->schemas as $schema) {
            DB::statement("DROP SCHEMA IF EXISTS \"{$schema}\" CASCADE");
        }
    }
};
