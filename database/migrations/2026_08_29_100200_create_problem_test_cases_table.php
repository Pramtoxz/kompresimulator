<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('problem_test_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('problem_id')->constrained('problems')->cascadeOnDelete();
            $table->string('label');
            $table->json('input');
            $table->json('expected');
            $table->boolean('is_hidden')->default(false);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->index(['problem_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('problem_test_cases');
    }
};
