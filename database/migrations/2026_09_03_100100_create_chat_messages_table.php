<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('auth.users')->cascadeOnDelete();
            $table->foreignId('attempt_id')->nullable()->constrained('attempts')->nullOnDelete();
            $table->string('role');
            $table->text('body');
            $table->boolean('refused')->default(false);
            $table->string('provider')->nullable();
            $table->string('model')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'id']);
            $table->index(['attempt_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
