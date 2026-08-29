<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('problem_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('problem_id')->constrained('problems')->cascadeOnDelete();
            $table->string('step_key');
            $table->unsignedSmallInteger('step_no');
            $table->text('instruction');
            $table->text('example_code')->nullable();
            $table->text('tips')->nullable();
            $table->timestamps();

            $table->unique(['problem_id', 'step_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('problem_guides');
    }
};
