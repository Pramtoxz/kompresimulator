<?php

use App\Enums\ProblemStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('auth.users')->cascadeOnDelete();
            $table->string('level');
            $table->string('framework');
            $table->string('status')->default(ProblemStatus::Queued->value);
            $table->string('thesis_title_snapshot');
            $table->string('title')->nullable();
            $table->text('brief')->nullable();
            $table->json('requirements')->nullable();
            $table->json('schema_spec')->nullable();
            $table->json('calc_rules')->nullable();
            $table->string('provider')->nullable();
            $table->string('model')->nullable();
            $table->string('prompt_version')->nullable();
            $table->json('raw_response')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('problems');
    }
};
