<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_check_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('attempts')->cascadeOnDelete();
            $table->foreignId('test_case_id')->nullable()->constrained('problem_test_cases')->nullOnDelete();
            $table->string('kind');
            $table->boolean('passed');
            $table->json('actual')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index(['attempt_id', 'kind']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_check_results');
    }
};
