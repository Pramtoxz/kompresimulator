<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai.ai_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('auth.users')->nullOnDelete();
            $table->string('purpose');
            $table->string('provider');
            $table->string('model');
            $table->string('prompt_version')->nullable();
            $table->json('payload')->nullable();
            $table->longText('response')->nullable();
            $table->unsignedInteger('prompt_tokens')->nullable();
            $table->unsignedInteger('completion_tokens')->nullable();
            $table->unsignedInteger('total_tokens')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            $table->boolean('succeeded')->default(false);
            $table->text('error')->nullable();
            $table->timestamps();

            $table->index(['purpose', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai.ai_requests');
    }
};
