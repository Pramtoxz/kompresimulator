<?php

use App\Enums\AttemptStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('auth.users')->cascadeOnDelete();
            $table->foreignId('problem_id')->constrained('problems')->cascadeOnDelete();
            $table->string('level');
            $table->string('status')->default(AttemptStatus::Running->value);
            $table->unsignedSmallInteger('current_step')->default(1);
            $table->unsignedSmallInteger('target_minutes');
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->string('duration_source')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'level', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
