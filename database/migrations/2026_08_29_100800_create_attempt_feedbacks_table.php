<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('attempts')->cascadeOnDelete();
            $table->string('kind');
            $table->text('body');
            $table->string('provider')->nullable();
            $table->string('model')->nullable();
            $table->timestamps();

            $table->index(['attempt_id', 'kind']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_feedbacks');
    }
};
