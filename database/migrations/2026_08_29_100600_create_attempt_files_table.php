<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('attempts')->cascadeOnDelete();
            $table->string('path');
            $table->string('step_key')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();

            $table->unique(['attempt_id', 'path']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_files');
    }
};
